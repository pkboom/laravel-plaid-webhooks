<?php

namespace Tests\Middlewares;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Pkboom\PlaidWebhooks\Middleware\VerifySignature;

class VerifySignatureTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::post('plaid-webhooks/{configKey?}', function () {
            return 'ok';
        })->middleware(VerifySignature::class);
    }

    /** @test */
    public function succeed_when_the_request_has_a_valid_signature()
    {
        $payload = ['event' => 'source.chargeable'];

        $response = $this->postJson(
            'plaid-webhooks',
            $payload,
            ['Plaid-Signature' => $this->determinePlaidSignature($payload)]
        );

        $response->assertStatus(200)
            ->assertSee('ok');
    }
}
