<?php

namespace Pkboom\PlaidWebhooks;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PlaidWebhooksServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/plaid-webhooks.php', 'plaid-webhooks');
    }
    
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/plaid-webhooks.php' => config_path('plaid-webhooks.php'),
            ], 'plaid-config');
        }

        if (! class_exists('CreatePlaidWebhookCallsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_plaid_webhook_calls_table.php.stub' => database_path('migrations/'.$timestamp.'_create_plaid_webhook_calls_table.php'),
            ], 'plaid-migration');
        }

        Route::macro('plaidWebhooks', function ($url) {
            return Route::post($url, PlaidWebhooksController::class);
        });
    }
}
