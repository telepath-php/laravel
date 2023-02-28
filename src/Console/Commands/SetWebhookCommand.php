<?php

namespace Telepath\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Telepath\Exceptions\TelegramException;
use Telepath\Laravel\Config\BotConfig;
use Telepath\Laravel\Contracts\WebhookResolver;
use Telepath\Laravel\Facades\Telepath;

class SetWebhookCommand extends Command
{

    protected $signature = 'telepath:set-webhook {name?} {hostname?}
        {--d|drop-pending-updates : Drop all pending updates}';

    protected $description = 'Sets the webhook for the given bot.';

    public function handle(): void
    {
        // Arguments
        $name = $this->argument('name') ?? config('telepath.default');
        $url = $this->url($name, $this->hostname());

        $this->comment("Setting webhook for '{$name}' to {$url}...");

        // Configuration
        $secretToken = config('telepath.webhook_secret') ?: null;

        // Options
        $dropPendingUpdates = $this->option('drop-pending-updates');

        $bot = Telepath::bot($name);

        try {
            $bot->setWebhook(
                url: $url,
                secret_token: $secretToken,
                drop_pending_updates: $dropPendingUpdates,
            );

            $this->info('Webhook set successfully!');
        } catch (TelegramException $e) {
            $this->error($e->getMessage());
        }
    }

    protected function hostname(): string
    {
        $hostname = $this->argument('hostname') ?? config('app.url');

        if (! str_starts_with($hostname, 'http')) {
            $hostname = 'https://' . $hostname;
        }

        return $hostname;
    }

    protected function url(string $botName, string $hostname): string
    {
        $secret = resolve(WebhookResolver::class)->create(
            BotConfig::load($botName)
        );

        $routeUrl = route('telepath.webhook', [
            'secret' => $secret,
        ], false);

        return $hostname . $routeUrl;
    }

}
