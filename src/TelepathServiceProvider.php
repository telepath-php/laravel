<?php

namespace Telepath\Laravel;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Telepath\Laravel\Config\BotConfig;
use Telepath\Laravel\Console\Commands\FetchCommand;
use Telepath\Laravel\Console\Commands\InstallCommand;
use Telepath\Laravel\Console\Commands\SetWebhookCommand;
use Telepath\Laravel\Contracts\WebhookResolver;
use Telepath\TelegramBot;

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
                $bot = new TelegramBot($config->apiToken);

                $bot->enableCaching(
                    new FilesystemAdapter(
                        directory: storage_path("app/telepath/cache/{$config->name}"),
                    )
                );

                $bot->discoverPsr4(
                    $config->directory ?? app_path('Telepath')
                );

                return $bot;
            });

        }

        // Register Webhook Resolver
        $this->app->bind(WebhookResolver::class, config('telepath.webhook_resolver'));
    }

    public function boot(): void
    {
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