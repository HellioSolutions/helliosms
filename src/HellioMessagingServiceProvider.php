<?php

namespace Hellio\HellioMessaging;

use Illuminate\Support\Arr;
use Hellio\HellioMessaging\Channels;
use Hellio\HellioMessaging\HellioSMS;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Hellio\HellioMessaging\Channels\HellioMessagingChannel;

class HellioMessagingServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/helliomessaging.php' => config_path('helliomessaging.php')
            ], 'helliomessaging');
        }

        Validator::extend('hellio_otp', function ($attribute, $value, $parameters, $validator) {
            $client = app(HellioSMS::class);
            $values = $validator->getData();
            $mobile_number = Arr::get($values, empty($parameters[0]) ? 'mobile_number' : $parameters[0]);
            return $client->verify($mobile_number, $value);
        });
    }

    public function register()
    {
        $this->app->bind(HellioSMS::class, function () {
            return new HellioSMS(env('helliomessaging.client_id'), env('helliomessaging.application_secret'));
        });
        $this->app->alias(HellioSMS::class, 'helliomessaging');
        Notification::extend('helliomessaging', function () {
            return new HellioMessagingChannel(app(HellioSMS::class));
        });
    }

    public function provides(): array
    {
        return ['helliomessaging'];
    }
}
