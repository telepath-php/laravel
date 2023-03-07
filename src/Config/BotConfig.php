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

    public static function load(string $name): static
    {
        return new static(
            $name,
            config("telepath.bots.{$name}")
        );
    }

    /**
     * @return static[]
     */
    public static function loadAll(): array
    {
        return collect(config('telepath.bots', []))
            ->mapWithKeys(fn(array $config, string $name) => [$name => new static($name, $config)])
            ->all();
    }

}