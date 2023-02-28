<?php

namespace Telepath\Laravel\Dotenv;

class DotenvEditor
{

    protected string $contents;

    public function __construct(
        protected string $filename = '.env'
    ) {
        $this->contents = file_get_contents(base_path($this->filename));
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

    public function save()
    {
        file_put_contents(base_path($this->filename), $this->contents);
    }

}