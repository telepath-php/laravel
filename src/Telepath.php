<?php

namespace Telepath\Laravel;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Hash;
use Telepath\TelegramBot;

class Telepath
{

    public function bot(string $name = null): ?TelegramBot
    {
        $name ??= config('telepath.default');

        return static::make($name)
            ?? throw new \InvalidArgumentException('Invalid bot name provided.');
    }

    public function fromSecret(string $secret): TelegramBot
    {
        $hash = base64_decode($secret);

        foreach (config('telepath.bots') as $name => $config) {

            if (Hash::check($config['api_token'], $hash)) {
                return static::make($name);
            }

        }

        abort(404);
    }

    protected function make(string $identifier): ?TelegramBot
    {
        try {
            return app("telepath.bot.{$identifier}");
        }
        catch (BindingResolutionException) {
            return null;
        }
    }

}