<?php

namespace Telepath\Laravel\WebhookResolver;

use Illuminate\Support\Facades\Hash;
use Telepath\Laravel\Config\BotConfig;
use Telepath\Laravel\Contracts\WebhookResolver;

/**
 * This Webhook Resolver uses Laravels own Hash::make function to create a secret.
 */
class HashWebhookResolver implements WebhookResolver
{

    public function create(BotConfig $config): string
    {
        return base64_encode(
            Hash::make($config->apiToken)
        );
    }

    public function resolve(string $secret): ?string
    {
        $hash = base64_decode($secret);

        foreach (BotConfig::loadAll() as $config) {
            if (Hash::check($config->apiToken, $hash)) {
                return $config->name;
            }
        }

        return null;
    }

}