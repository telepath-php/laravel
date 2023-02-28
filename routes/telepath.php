<?php

use Illuminate\Support\Facades\Route;
use Telepath\Laravel\Http\Middleware\ResolveWebhook;
use Telepath\Laravel\Http\Middleware\ValidateSecretToken;
use Telepath\TelegramBot;

Route::post('/telepath/bot/{secret}', function (TelegramBot $bot) {

    $bot->handleWebhook();

})
    ->name('telepath.webhook')
    ->middleware([
        ResolveWebhook::class,
        ValidateSecretToken::class,
    ]);