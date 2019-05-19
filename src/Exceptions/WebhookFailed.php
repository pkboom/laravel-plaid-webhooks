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
        return new static("The signature `{$signature}` found in the header named `Plaid-Signature` is invalid. Make sure that the `plaid-webhooks.signing_secret` config key is set to the value you found on the Plaid dashboard. If you are caching your config try running `php artisan cache:clear` to resolve the problem.");
    }

    public static function signingSecretNotSet()
    {
        return new static('The Plaid webhook signing secret is not set. Make sure that the `plaid-webhooks.signing_secret` config key is set to the value you found on the Plaid dashboard.');
    }

    public static function missingType($id)
    {
        return new static("Webhook call id `{$id}` did not contain a type. Valid Plaid webhook calls should always contain a type.");
    }

    public function render($request)
    {
        return response(['error' => $this->getMessage()], 400);
    }
}
