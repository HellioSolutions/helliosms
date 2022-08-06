<?php

namespace Hellio\HellioMessaging;

use Hellio\HellioMessaging\Channels;
use Hellio\HellioMessaging\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
            $this->mergeConfigFrom(__DIR__.'/../config/helliomessaging.php', 'helliomessaging');
            $this->publishAssets();

        Validator::extend('hellio_otp', function ($attribute, $value, $parameters, $validator) {
            $client = app(Client::class);
            $values = $validator->getData();
            $mobile_number = Arr::get($values, empty($parameters[0]) ? 'mobile_number' : $parameters[0]);
            return $client->verify($mobile_number, $value);
        });
    }

       protected function publishAssets()
    {
        if (!$this->app->runningInConsole() || Str::contains($this->app->version(), 'lumen'))
            return;

        $this->publishes([__DIR__.'/../config/helliomessaging.php' => config_path('helliomessaging.php')]);
    }


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


     public function provides()
    {
        return ['helliomessaging'];
    }
}
