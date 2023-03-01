<?php

namespace Telepath\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Telepath\Exceptions\TelegramException;
use Telepath\Laravel\Config\BotConfig;
use Telepath\Laravel\Contracts\WebhookResolver;
use Telepath\Laravel\Facades\Telepath;

class SetWebhookCommand extends Command
{

    protected $signature = 'telepath:set-webhook {hostname?}
        {--b|bot= : Identifier of the bot to use}
        {--d|drop-pending-updates : Drop all pending updates}';

    protected $description = 'Sets the webhook for the given bot.';

    public function handle(): void
    {
        $name = $this->option('bot') ?? config('telepath.default');

        try {
            $bot = Telepath::bot($name);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return;
        }

        $url = $this->url($name);

        $this->comment("Setting webhook for '{$name}' bot to {$url}...");

        // Configuration
        $secretToken = config('telepath.webhook.secret') ?: null;

        // Options
        $dropPendingUpdates = $this->option('drop-pending-updates');

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

    protected function url(string $botName): string
    {
        $secret = resolve(WebhookResolver::class)->create(
            BotConfig::load($botName)
        );

        $routeUrl = route('telepath.webhook', [
            'secret' => $secret,
        ], false);

        return $this->hostname() . $routeUrl;
    }

}
