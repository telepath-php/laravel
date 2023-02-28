<?php

namespace Telepath\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Telepath\Exceptions\TelegramException;
use Telepath\Laravel\Facades\Telepath;

class FetchCommand extends Command
{

    protected $signature = 'telepath:fetch
        {--b|bot= : Identifier of the bot to use}';

    protected $description = 'Fetches Telegram updates periodically.';

    public function handle(): void
    {
        $name = $this->option('bot') ?? config('telepath.default');

        try {
            $bot = Telepath::bot($name);
        } catch (\InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return;
        }

        try {
            $bot->deleteWebhook();
        } catch (TelegramException $e) {
            // Do nothing
        }

        $this->info("Fetching updates for '{$name}' bot...");
        $bot->handlePolling();
    }

}
