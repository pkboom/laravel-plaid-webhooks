<?php

namespace Pkboom\PlaidWebhooks;

use Illuminate\Database\Eloquent\Model;
use Pkboom\PlaidWebhooks\Exceptions\WebhookFailed;

class PlaidWebhookCall extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'error' => 'array',
        'exception' => 'array',
    ];

    public function process()
    {
        if ($this->type === '') {
            throw WebhookFailed::missingType($this->id);
        }

        event("plaid-webhooks::{$this->type}", $this);

        $jobClass = $this->determineJobClass($this->type);
        
        if ($jobClass === '') {
            return;
        }
        
        dispatch(new $jobClass($this));
    }

    public function determineJobClass($eventType)
    {
        return config("plaid-webhooks.jobs.{$eventType}", '');
    }

    public function saveException(\Exception $exception)
    {
        $this->exception = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ];

        $this->save();

        return $this;
    }
}
