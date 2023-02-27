<?php

use Illuminate\Support\Facades\Route;
use Telepath\Laravel\Http\Middleware\ResolveWebhook;
use Telepath\TelegramBot;

Route::post('/telepath/bot/{bot}', function (TelegramBot $bot) {

    $bot->handleWebhook();

})
    ->name('telepath.webhook')
    ->middleware([ResolveWebhook::class]);