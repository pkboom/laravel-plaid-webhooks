# Handle Plaid Webhooks in a Laravel application

Before using this package we highly recommend reading [the entire documentation on webhooks over at Plaid](https://plaid.com/docs/#auth-webhooks).

## Installation

You can install the package via composer:

```bash
composer require pkboom/laravel-plaid-webhooks
```

The service provider will automatically register itself.

You must publish the config file with:
```bash
php artisan vendor:publish --tag="plaid-config"
```

This is the contents of the config file that will be published at `config/stripe-webhooks.php`:

```php
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

```

Next, you must create the `plaid_webhook_calls` table:
```bash
php artisan vendor:publish --tag="plaid-migration"
php artisan migrate
```

Finally, take care of the routing: you must set up Plaid webhooks when you [create an item](https://plaid.com/docs/#creating-items-with-plaid-link). In the routes file of your app you must pass that route to `Route::plaidWebhooks`:

```php
Route::plaidWebhooks('webhook-url');
```

Behind the scenes this will register a `POST` route to a controller provided by this package. Because Plaid has no way of getting a csrf-token, you must add that route to the `except` array of the `VerifyCsrfToken` middleware:

```php
protected $except = [
    'webhook-url',
];
```


## Usage
For usage, refer to [spatie/laravel-stripe-webhooks](https://github.com/spatie/laravel-stripe-webhooks).

### Testing

``` bash
composer test
```

## License

The MIT License (MIT). Please see [MIT license](http://opensource.org/licenses/MIT) for more information.