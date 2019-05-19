<?php

namespace Pkboom\PlaidWebhooks\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pkboom\PlaidWebhooks\PlaidWebhooksClass
 */
class PlaidWebhooksFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-plaid-webhooks';
    }
}
