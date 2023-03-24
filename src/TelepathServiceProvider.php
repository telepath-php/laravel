<?php

namespace Telepath\Laravel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Telepath\Bot;
use Telepath\Facades\BotBuilder;
use Telepath\Laravel\Config\BotConfig;
use Telepath\Laravel\Console\Commands\FetchCommand;
use Telepath\Laravel\Console\Commands\InstallCommand;
use Telepath\Laravel\Console\Commands\SetWebhookCommand;
use Telepath\Laravel\Contracts\WebhookResolver;
use Telepath\Laravel\Events\EventDispatcherBridge;

class TelepathServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/telepath.php', 'telepath'
        );

        // Register the Telepath class
        $this->app->singleton('telepath', Telepath::class);

        // Configure and register the bot instances (lazy)
        foreach (BotConfig::loadAll() as $name => $config) {

            $this->app->singleton("telepath.bot.{$name}", function () use ($config) {

                return BotBuilder::token($config->apiToken)
                    ->handlersIn($config->directory ?? app_path('Telepath'))
                    ->useEventDispatcher(new EventDispatcherBridge(app('events')))
                    ->useServiceContainer(app())
                    ->build();

            });

            // Allow auto-wiring of the default bot inside Laravel
            if ($name === config('telepath.default')) {
                $this->app->singleton(Bot::class, fn() => resolve("telepath.bot.{$name}"));
            }

        }

        // Register Webhook Resolver
        $this->app->bind(WebhookResolver::class, config('telepath.webhook.resolver'));
    }

    public function boot(): void
    {
        Route::middlewareGroup('telepath', config('telepath.webhook.middleware', []));

        $this->loadRoutesFrom(
            __DIR__ . '/../routes/telepath.php'
        );

        $this->publishes([
            __DIR__ . '/../config/telepath.php' => config_path('telepath.php'),
        ], 'telepath-config');

        $this->commands([
            InstallCommand::class,
            FetchCommand::class,
            SetWebhookCommand::class,
        ]);
    }

}