<?php

use Illuminate\Support\Facades\Route;
use Telepath\Laravel\Contracts\WebhookResolver;
use Telepath\Laravel\Facades\Telepath;

Route::post('/telepath/bot/{secret}', function (string $secret, WebhookResolver $secretResolver) {

    $bot = Telepath::bot(
        $secretResolver->resolve($secret)
    );

    $bot->handleWebhook();

})->name('telepath.webhook');