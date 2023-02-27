<?php

namespace Telepath\Laravel;

use Illuminate\Support\ServiceProvider;
use Telepath\TelegramBot;

class TelepathServiceProvider extends ServiceProvider
{

    public function register(): void
    {

        // Register the Telepath class
        $this->app->singleton('telepath', function () {
            return new Telepath;
        });

        // Configure and register the bot instances (lazy)
        foreach (config('telepath.bots') as $name => $config) {
            $this->app->singleton("telepath.bot.{$name}", function () use ($config) {
                return new TelegramBot($config['api_token']);
            });
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/telepath.php', 'telepath'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(
            __DIR__ . '/../routes/telepath.php'
        );

        $this->publishes([
            __DIR__ . '/../config/telepath.php' => config_path('telepath.php'),
        ]);
    }

}