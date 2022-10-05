<?php

use Illuminate\Support\Facades\Route;
use Telepath\TelegramBot;

Route::post('/api/telegram/webhook/{$token}', static function (TelegramBot $bot, $token) {

    if ($token !== config('telepath.bot.api_token')) {
        abort(400);
    }

    $bot->handleWebhook();

})->middleware('telegram.network')->name('telegram.webhook');