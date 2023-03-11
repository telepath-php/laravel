<?php

use Illuminate\Support\Facades\Route;
use Telepath\Bot;

Route::name('telepath.webhook')
    ->middleware('telepath')
    ->post('/telepath/bot/{secret}', function (Bot $bot) {

        $bot->handleWebhook();

    });