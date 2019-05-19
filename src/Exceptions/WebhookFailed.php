<?php

namespace Pkboom\PlaidWebhooks\Exceptions;

use Exception;

class WebhookFailed extends Exception
{
    public static function missingSignature()
    {
        return new static('The request did not contain a header named `Plaid-Signature`.');
    }
    
    public static function invalidSignature($signature)
    {
        return new static("The signature `{$signature}` found in the header named `Stripe-Signature` is invalid. Make sure that the `services.stripe.webhook_signing_secret` config key is set to the value you found on the Stripe dashboard. If you are caching your config try running `php artisan cache:clear` to resolve the problem.");
    }
}
