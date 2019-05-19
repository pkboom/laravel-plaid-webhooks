<?php

return [

    /*
     * You can define the job that should be run when a certain webhook hits your application here.
     *
     * You can find a list of Plaid webhook types here:
     * https://plaid.com/docs/#auth-webhooks
     */
    'jobs' => [
        // 'AUTH' => \App\Jobs\PlaidWebhookCall\AUTH::class,
    ],

    /*
     * The classname of the model to be used. The class should equal or extend
     * Pkboom\PlaidWebhooks\PlaidWebhookCall.
     */
    'model' => Pkboom\PlaidWebhooks\PlaidWebhookCall::class,
];
