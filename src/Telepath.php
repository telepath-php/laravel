<?php

namespace Telepath\Laravel;

use Illuminate\Contracts\Container\BindingResolutionException;
use Telepath\Bot;

class Telepath
{

    public function bot(string $name = null): Bot
    {
        $name ??= config('telepath.default');

        try {
            return app("telepath.bot.{$name}");
        } catch (BindingResolutionException) {
            throw new \InvalidArgumentException('Invalid bot name provided.');
        }
    }

}