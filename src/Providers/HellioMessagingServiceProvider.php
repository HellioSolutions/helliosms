<?php

namespace Hellio\HellioMessaging\Providers;

use Hellio\HellioMessaging\Channels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
            $client = app(Client::class);
            $values = $validator->getData();
            $mobile_number = Arr::get($values, empty($parameters[0]) ? 'mobile_number' : $parameters[0]);
            return $client->verify($mobile_number, $value);
        });
    }

    public function register()
    {
        $this->app->bind(Client::class, function () {
            return new Client(env('helliomessaging.client_id'), env('helliomessaging.application_secret'));
        });
        $this->app->alias(Client::class, 'helliomessaging');
        Notification::extend('helliomessaging', function () {
            return new Channels\HellioMessagingChannel(app(Client::class));
        });
    }

    public function provides(): array
    {
        return ['helliomessaging'];
    }
}
