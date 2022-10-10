<?php

namespace Telepath\Laravel\Console\Commands;

use Telepath\Exceptions\TelegramException;
use Telepath\TelegramBot;
use Telepath\Types\InputFile;

class SetWebhook extends \Illuminate\Console\Command
{

    protected $signature = 'webhook:set
        {hostname? : Hostname of this application}
        {--c|certificate= : Path to your public key certificate}
        {--i|ip-address= : The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS.}
        {--m|max-connections= : The maximum allowed number of simultaneous HTTPS connections to the webhook.}
        {--a|allowed-updates= : List of the update types you want your bot to receive (comma-seperated).}
        {--d|drop-pending-updates : Drop all pending updates.}
        {--s|secret-token= : Secret token to be sent in a header "X-Telegram-Bot-Api-Secret-Token"}
    ';

    protected $description = 'Specify a URL and receive incoming updates via an outgoing webhook.';

    public function __construct(
        protected TelegramBot $bot
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $url = $this->buildUrl();
        $certificate = $this->option('certificate');
        $ipAddress = $this->option('ip-address');
        $maxConnections = $this->option('max-connections');
        $allowedUpdates = $this->option('allowed-updates');
        $dropPendingUpdates = $this->option('drop-pending-updates') ?: null;
        $secretToken = $this->option('secret-token');

        $certificateFile = $certificate ? InputFile::fromFile($certificate) : null;

        $allowedUpdates = str($allowedUpdates)->explode(',')->toArray();

        $this->info("Setting Webhook URL to: {$url}");

        try {
            $this->bot->setWebhook(
                url: $url,
                certificate: $certificateFile,
                ip_address: $ipAddress,
                max_connections: $maxConnections,
                allowed_updates: $allowedUpdates,
                drop_pending_updates: $dropPendingUpdates,
                secret_token: $secretToken,
            );
        } catch (TelegramException $e) {
            $this->error($e->getMessage());
            return;
        }

        $this->info($this->bot->lastApiResult() ?? 'Webhook was set');
    }

    public function buildUrl(): string
    {
        $hostname = $this->argument('hostname');
        if (! $hostname) {
            $this->ask('Hostname of your application: ', config('app.url'));
        }

        if (! str_starts_with($hostname, 'http')) {
            $hostname = (app()->environment() === 'local' ? 'http' : 'https') . "://{$hostname}";
        }

        return $hostname . route('telegram.webhook', [
                config('telepath.bot.api_token'),
            ], false);
    }

}