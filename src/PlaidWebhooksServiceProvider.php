<?php

namespace Pkboom\PlaidWebhooks;

use Illuminate\Support\ServiceProvider;

class PlaidWebhooksServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton('laravel-plaid-webhooks', function () {
            return new PlaidWebhooks();
        });

        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-plaid-webhooks');
    }
    
    public function boot()
    {
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         FooCommand::class,
        //     ]);
        // }
        // $this->loadViewsFrom(__DIR__.'/path/to/views', 'courier');
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        // $this->publishes([
            // __DIR__.'/../config/laravel-plaid-webhooks.php' => config_path('laravel-plaid-webhooks.php'),
        // ]);
        // Blade::directive('some', function () {
        //     return '<style>'. $this->app->make('some')->styles() .'</style>';
        // });
    }
}
