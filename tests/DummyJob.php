<?php

namespace Tests;

class DummyJob
{
    public $plaidWebhookCall;

    public function __construct($plaidWebhookCall)
    {
        $this->plaidWebhookCall = $plaidWebhookCall;
    }

    public function handle()
    {
    }
}
