<?php

namespace Telepath\Laravel;

use Illuminate\Contracts\Container\BindingResolutionException;
use Telepath\TelegramBot;

class Telepath
{

    public function bot(string $name = null): ?TelegramBot
    {
        $name ??= config('telepath.default');

        return static::make($name)
            ?? throw new \InvalidArgumentException('Invalid bot name provided.');
    }

    protected function make(string $identifier): ?TelegramBot
    {
        try {
            return app("telepath.bot.{$identifier}");
        } catch (BindingResolutionException) {
            return null;
        }
    }

}