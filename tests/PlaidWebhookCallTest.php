<?php

namespace Tests;

use Exception;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Pkboom\PlaidWebhooks\PlaidWebhookCall;

class PlaidWebhookCallTest extends TestCase
{
    /** @var \Pkboom\PlaidWebhooks\PlaidWebhookCall */
    public $plaidWebhookCall;

    public function setUp(): void
    {
        parent::setUp();

        Bus::fake();

        Event::fake();

        config(['plaid-webhooks.jobs' => ['my_type' => DummyJob::class]]);

        $this->plaidWebhookCall = PlaidWebhookCall::create([
            'type' => 'my.type',
            'payload' => ['name' => 'value'],
        ]);
    }

    /** @test */
    public function fire_off_the_configured_job()
    {
        $this->plaidWebhookCall->process();

        Bus::assertDispatched(DummyJob::class, function ($job) {
            return $job->plaidWebhookCall->id === $this->plaidWebhookCall->id;
        });
    }
    
    /** @test */
    public function it_will_not_dispatch_a_job_for_another_type()
    {
        config(['plaid-webhooks.jobs' => ['another_type' => DummyJob::class]]);

        $this->plaidWebhookCall->process();
        
        Bus::assertNotDispatched(DummyJob::class);
    }

    /** @test */
    public function it_will_not_dispatch_jobs_when_no_jobs_are_configured()
    {
        config(['plaid-webhooks.jobs' => []]);

        $this->plaidWebhookCall->process();

        Bus::assertNotDispatched(DummyJob::class);
    }

    /** @test */
    public function dispatch_events_even_when_no_corresponding_job_is_configured()
    {
        config(['plaid-webhooks.jobs' => ['another_type' => Dummy::class]]);

        $this->plaidWebhookCall->process();

        Event::assertDispatched("plaid-webhooks::{$this->plaidWebhookCall->type}", function ($event, $eventPayload) {
            if (! $eventPayload instanceof PlaidWebhookCall) {
                return false;
            }

            return $eventPayload->id === $this->plaidWebhookCall->id;
        });
    }

    /** @test */
    public function save_an_exception()
    {
        $this->plaidWebhookCall->saveException(new Exception('my message', 123));

        $this->assertEquals(123, $this->plaidWebhookCall->exception['code']);
        $this->assertEquals('my message', $this->plaidWebhookCall->exception['message']);
    }

    /** @test */
    public function clear_the_exception()
    {
        $this->plaidWebhookCall->saveException(new Exception('my message', 123));

        $this->plaidWebhookCall->process();

        $this->assertNull($this->plaidWebhookCall->exception);
    }
}
