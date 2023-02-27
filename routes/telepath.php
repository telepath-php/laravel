<?php

use Illuminate\Support\Facades\Route;
use Telepath\Laravel\Facades\Telepath;

Route::post('/telepath/bot/{secret}', function(string $secret) {

    $bot = Telepath::fromSecret($secret);

    $bot->handleWebhook();

})->name('telepath.webhook');