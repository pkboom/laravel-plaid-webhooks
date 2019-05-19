<?php

namespace Pkboom\PlaidWebhooks\Middleware;

use Closure;
use Pkboom\PlaidWebhooks\Exceptions\WebhookFailed;
use Stripe\Webhook;

class VerifySignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signature = $request->header('Plaid-Signature');

        if (! $signature) {
            throw WebhookFailed::missingSignature();
        }
        
        if (! $this->isValid($signature, $request->getContent())) {
            throw WebhookFailed::invalidSignature($signature);
        }

        return $next($request);
    }

    public function isValid($signature, $payload)
    {
        $secret = config('plaid-webhooks.signing_secret');

        try {
            Webhook::constructEvent($payload, $signature, $secret);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}
