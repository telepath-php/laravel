<?php

use Illuminate\Support\Facades\Route;
use Telepath\TelegramBot;

Route::name('telepath.webhook')
    ->middleware('telepath')
    ->post('/telepath/bot/{secret}', function (TelegramBot $bot) {

        $bot->handleWebhook();

    });