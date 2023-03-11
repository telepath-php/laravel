<?php

namespace Telepath\Laravel\Contracts;

use Telepath\Laravel\Config\BotConfig;

interface WebhookResolver
{

    public function create(BotConfig $config): string;

    public function resolve(string $secret): ?string;

}