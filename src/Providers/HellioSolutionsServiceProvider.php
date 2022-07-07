<?php

namespace HellioSolutions\HellioMessaging\Providers;

use HellioSolutions\HellioMessaging\Channels;
use HellioSolutions\HellioMessaging\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use function HellioSolutions\HellioMessaging\app;
use function HellioSolutions\HellioMessaging\config_path;
use function HellioSolutions\HellioMessaging\env;

/**
 * Class HellioSolutionsServiceProvider
 * @package HellioSolutions\HellioMessaging
 */
class HellioSolutionsServiceProvider extends LaravelServiceProvider
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
        $this->publishes([
            __DIR__ . '/../config/helliomessaging.php' => config_path('helliomessaging.php'),
        ], 'helliomessaging');

        Validator::extend('helliomessaging_otp', function ($attribute, $value, $parameters, $validator) {
            $client = app(Client::class);
            $values = $validator->getData();
            $mobile_number = Arr::get($values, empty($parameters[0]) ? 'mobile_number' : $parameters[0]);
            return $client->verify($mobile_number, $value);
        });
    }
}
