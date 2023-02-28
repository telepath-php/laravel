<?php

namespace Telepath\Laravel\Dotenv;

class DotenvEditor
{

    public function __construct(
        protected string $filename = '.env'
    ) {}

    public function get(string $var): ?string
    {
        $contents = file_get_contents(base_path($this->filename));

        if (preg_match("/^{$var}=(.*)$/m", $contents, $matches)) {
            return trim($matches[1], ' \t\n\r\0\x0B"');
        }

        return null;
    }

    public function has(string $var): bool
    {
        $contents = file_get_contents(base_path($this->filename));

        if (! preg_match("/^{$var}=/m", $contents)) {
            return false;
        }

        return true;
    }

    public function set(string $var, mixed $value = ''): void
    {
        $contents = file_get_contents(base_path($this->filename));

        $value = $this->prepareValue($value);

        if (preg_match("/^{$var}=(.*)$/m", $contents, $matches)) {
            $contents = str_replace($matches[0], "{$var}={$value}", $contents);
        } else {
            $contents .= "\n{$var}={$value}";
        }

        file_put_contents(base_path($this->filename), $contents);
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

}