<?php

namespace Telepath\Laravel\Dotenv;

class DotenvEditor
{

    public function __construct(
        protected string $contents = '',
        protected string $filename = '.env',
    ) {}

    public static function loadFile(string $filename): static
    {
        return new static(
            file_get_contents($filename),
            $filename,
        );
    }

    public function get(string $var): ?string
    {
        if (preg_match("/^{$var}=(.*)$/m", $this->contents, $matches)) {
            return trim($matches[1], ' \t\n\r\0\x0B"');
        }

        return null;
    }

    public function has(string $var): bool
    {
        if (! preg_match("/^{$var}=/m", $this->contents)) {
            return false;
        }

        return true;
    }

    public function set(string $var, mixed $value = ''): void
    {
        $value = $this->prepareValue($value);

        if (preg_match("/^{$var}=(.*)$/m", $this->contents, $matches)) {
            $this->contents = str_replace($matches[0], "{$var}={$value}", $this->contents);
        } else {
            $this->contents .= "\n{$var}={$value}";
        }
    }

    protected function prepareValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (str_contains($value, ' ')) {
            return '"' . $value . '"';
        }

        return (string) $value;
    }

    public function save(string $filename = null): void
    {
        file_put_contents(
            $filename ?? $this->filename,
            $this->contents
        );
    }

}