<?php

namespace Telepath\Laravel;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Telepath\Laravel\Console\Commands\SetWebhook;
use Telepath\Laravel\Http\Middleware\TrustTelegramNetwork;
use Telepath\TelegramBot;

class TelepathServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/telepath.php', 'telepath');

        $this->app->singleton(TelegramBot::class, function() {
            $bot = new TelegramBot(
                config('telepath.bot.api_token'),
                config('telepath.bot.api_url', 'https://api.telegram.org')
            );

            if ($proxy = config('telepath.proxy')) {
                $bot->enableProxy($proxy);
            }

            File::ensureDirectoryExists(app_path('Telegram'));
            $bot->discoverPsr4(app_path('Telegram'));

            $bot->enableCaching(new FilesystemAdapter(
                directory: storage_path('telepath'),
            ));

            return $bot;
        });
    }

    public function boot()
    {
        // Routes should be able to be overwritten
        if (file_exists(base_path('routes/telepath.php'))) {
            $this->loadRoutesFrom(base_path('routes/telepath.php'));
        } else {
            $this->loadRoutesFrom(__DIR__ . '/../routes/telepath.php');
        }

        // Register Middleware for trusted Telegram networks
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('telegram.network', TrustTelegramNetwork::class);

        // Publish commands if on console
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    protected function bootForConsole()
    {
        // Publishing config and routes in different groups
        $this->publishes([
            __DIR__ . '/../config/telepath.php' => config_path('telepath.php'),
        ], 'telepath-config');

        $this->publishes([
            __DIR__ . '/../routes/telepath.php' => base_path('routes/telepath.php'),
        ], 'telepath-routes');

        // Registering package commands
        $this->commands([
            SetWebhook::class,
        ]);
    }

}