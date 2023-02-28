<?php

namespace Telepath\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InstallCommand extends Command
{

    protected $signature = 'telepath:install {--f|force : Overwrite any existing files}';

    protected $description = 'Installs Telepath';

    public function handle(): void
    {
        // Copy config file
        $this->callSilent('vendor:publish', [
            '--tag'   => 'telepath-config',
            '--force' => $this->option('force'),
        ]);

        // Add TELEGRAM_API_TOKEN to .env and .env.example
        $this->addEnvVariable('TELEGRAM_API_TOKEN');

        // Add TELEGRAM_WEBHOOK_SECRET to .env and .env.example
        $this->addEnvVariable('TELEGRAM_WEBHOOK_SECRET', Str::random(32));

        // Make sure the Handler directory exists
        File::ensureDirectoryExists(app_path('Telepath'));

        $this->info('Nova installed successfully.');
        $this->newLine();

        $this->line('Set your Bot API Token in your .env file and you\'re good to go!');
    }

    protected function addEnvVariable(string $name, string $value = '')
    {
        foreach (['.env', '.env.example'] as $filename) {

            // Check if variable already exists
            $contents = file_get_contents(base_path($filename));

            if (preg_match("/^{$name}=/m", $contents)) {
                continue;
            }

            if ($filename === '.env.example') {
                $value = '';
            }

            // Add to file
            $file = fopen(base_path($filename), 'a');
            fwrite($file, "\n{$name}={$value}");
            fclose($file);
        }
    }

}
