<?php

namespace Tests;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Pkboom\PlaidWebhooks\PlaidWebhookCall;

class IntegrationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Event::fake();

        Bus::fake();

        Route::plaidWebhooks('plaid-webhooks');

        config(['plaid-webhooks.jobs' => ['my_type' => DummyJob::class]]);
        config(['plaid-webhooks.model' => PlaidWebhookCall::class]);
    }

    /** @test */
    public function handle_a_valid_request()
    {
        $payload = [ 'webhook_type' => 'my.type', 'payload' => 'value' ];

        $this->postJson('plaid-webhooks', $payload)
            ->assertSuccessful();

        $this->assertCount(1, PlaidWebhookCall::all());

        $webhookCall = PlaidWebhookCall::first();

        Event::assertDispatched('plaid-webhooks::my.type', function ($event, $eventPayload) use ($webhookCall) {
            if (! $eventPayload instanceof PlaidWebhookCall) {
                return false;
            }

            return $eventPayload->id === $webhookCall->id;
        });

        Bus::assertDispatched(DummyJob::class, function (DummyJob $job) use ($webhookCall) {
            return $job->plaidWebhookCall->id === $webhookCall->id;
        });
    }

    /** @test */
    public function a_request_with_an_invalid_payload_will_be_logged_without_dispatching_an_event_or_job()
    {
        $payload = ['invalid_payload'];

        $this->postJson('plaid-webhooks', $payload)
            ->assertStatus(400);

        $this->assertCount(1, PlaidWebhookCall::all());

        Event::assertNotDispatched('plaid-webhooks::my.type');

        Bus::assertNotDispatched(DummyJob::class);
    }
}
