<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pkboom\PlaidWebhooks\PlaidWebhooksServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            PlaidWebhooksServiceProvider::class,
        ];
    }

    /**
     * Set up the environment
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config(['plaid-webhooks.signing_secret' => 'test_signing_secret']);
    }

    public function setUpDatabase()
    {
        include_once __DIR__ . '/../database/migrations/create_plaid_webhook_calls_table.php.stub';

        (new \CreatePlaidWebhookCallsTable())->up();
    }

    public function determinePlaidSignature(array $payload)
    {
        $secret = config('plaid-webhooks.signing_secret');
        
        $timestamp = time();

        $timestampPayload = $timestamp.'.'.json_encode($payload);

        $signature = hash_hmac('sha256', $timestampPayload, $secret);

        return "t={$timestamp},v1={$signature}";
    }
}
