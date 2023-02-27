<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Telegram Bot
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the bots below you wish to use as
    | your default bot. If you only need one bot in your application
    | you don't need to worry about it.
    |
    */
    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Telegram Bots
    |--------------------------------------------------------------------------
    |
    | If you need more than one bot in your application, you can define
    | them here. Else it is sufficient to use the environment vars
    | that are preconfigured for the default bot.
    |
    */
    'bots'    => [

        'main' => [
            'api_token' => env('TELEGRAM_API_TOKEN'),
            'username'  => env('TELEGRAM_USERNAME'),
        ],

    ],

];