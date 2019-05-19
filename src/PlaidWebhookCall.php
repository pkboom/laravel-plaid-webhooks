<?php

namespace Pkboom\PlaidWebhooks;

use Illuminate\Database\Eloquent\Model;
use Tests\DummyJob;

class PlaidWebhookCall extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
    ];

    public function process()
    {
        $this->clearException();
        
        event("plaid-webhooks::{$this->type}", $this);

        $jobClass = $this->determineJobClass($this->type);
        
        if ($jobClass === '') {
            return;
        }
        
        dispatch(new $jobClass($this));
    }

    public function determineJobClass($eventType)
    {
        $jobConfigType = str_replace('.', '_', $eventType);

        return config("plaid-webhooks.jobs.{$jobConfigType}", '');
    }

    public function saveException(\Exception $exception)
    {
        $this->exception = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ];
    }

    public function clearException()
    {
        $this->exception = null;

        $this->save();

        return $this;
    }
}
