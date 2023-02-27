<?php

namespace Telepath\Laravel\Config;

use Illuminate\Support\Str;

readonly class BotConfig
{

    public string $name;

    public string $apiToken;

    public ?string $directory;

    public function __construct(string $name, array $config)
    {
        $this->name = $name;

        foreach ($config as $key => $value) {

            $camel = Str::camel($key);
            $this->{$camel} = $value;

        }
    }

    /**
     * @return static[]
     */
    public static function load(): array
    {
        $bots = [];

        foreach (config('telepath.bots', []) as $name => $config) {

            $bots[$name] = new static($name, $config);

        }

        return $bots;
    }

}