<?php

namespace Pkboom\PlaidWebhooks;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PlaidWebhooksController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->input();

        $modelClass = config('plaid-webhooks.model');

        $plaidWebhookCall = $modelClass::create([
            'type' => $payload['webhook_type'] ?? '',
            'payload' => $payload,
            'error' => $payload['error'] ?? '',
        ]);

        try {
            $plaidWebhookCall->process();
        } catch (\Exception $exception) {
            $plaidWebhookCall->saveException($exception);

            throw $exception;
        }

        return response()->json(['message' => 'ok']);
    }
}
