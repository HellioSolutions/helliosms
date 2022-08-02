<?php

namespace Hellio\HellioMessaging\Providers;

use Hellio\HellioMessaging\Channels;
use Hellio\HellioMessaging\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use function Hellio\HellioMessaging\app;
use function Hellio\HellioMessaging\config_path;
use function Hellio\HellioMessaging\env;

/**
 * Class HellioMessagingServiceProvider
 * @package Hellio\HellioMessaging\Providers
 */

class HellioMessagingServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/helliomessaging.php', 'helliomessaging');
        $this->app->bind(Client::class, function () {
            return new Client(env('helliomessaging.client_id'), env('helliomessaging.application_secret'));
        });
        $this->app->alias(Client::class, 'helliomessaging');
        Notification::extend('helliomessaging', function () {
            return new Channels\HellioMessagingChannel(app(Client::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
          $config = realpath(__DIR__ . '/../config/helliomessaging.php');
          $this->publishes([
            $config => config_path('helliomessaging.php'),
        ]);

        Validator::extend('hellio_otp', function ($attribute, $value, $parameters, $validator) {
            $client = app(Client::class);
            $values = $validator->getData();
            $mobile_number = Arr::get($values, empty($parameters[0]) ? 'mobile_number' : $parameters[0]);
            return $client->verify($mobile_number, $value);
        });
    }

     public function provides()
    {
        return ['helliomessaging'];
    }
}
