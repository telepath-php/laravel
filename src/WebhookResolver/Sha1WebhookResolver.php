<?php

namespace Telepath\Laravel\WebhookResolver;

use Telepath\Laravel\Config\BotConfig;
use Telepath\Laravel\Contracts\WebhookResolver;

/**
 * This Webhook Resolver uses SHA-1 to generate a secret.
 */
class Sha1WebhookResolver implements WebhookResolver
{

    public function create(BotConfig $config): string
    {
        return sha1($config->apiToken);
    }

    public function resolve(string $secret): ?string
    {
        foreach (BotConfig::loadAll() as $name => $config) {
            if (sha1($config->apiToken) === $secret) {
                return $name;
            }
        }

        return null;
    }


}