<?php

namespace Telepath\Laravel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Telepath\Laravel\Dotenv\DotenvEditor;

class InstallCommand extends Command
{

    protected $signature = 'telepath:install 
        {--f|force : Overwrite any existing files}';

    protected $description = 'Installs Telepath';

    public function handle(): void
    {
        // Copy config file
        $this->callSilent('vendor:publish', [
            '--tag'   => 'telepath-config',
            '--force' => $this->option('force'),
        ]);

        $this->setupEnvVariables();

        // Make sure the Handler directory exists
        File::ensureDirectoryExists(app_path('Telepath'));

        $this->info("Telepath installed successfully.\n");
    }

    protected function setupEnvVariables()
    {
        $env = DotenvEditor::loadFile(base_path('.env'));

        // Setup TELEGRAM_API_TOKEN
        if (! $env->get('TELEGRAM_API_TOKEN') || $this->option('force')) {
            $apiToken = $this->ask('If you already have a Bot API Token, enter it here');
            $env->set('TELEGRAM_API_TOKEN', $apiToken);
        }

        // Setup TELEGRAM_WEBHOOK_SECRET
        if (! $env->has('TELEGRAM_WEBHOOK_SECRET') || $this->option('force')) {
            $env->set('TELEGRAM_WEBHOOK_SECRET', Str::random(32));
        }

        $env->save();

        if (! File::exists(base_path('.env.example'))) {
            return;
        }

        $example = DotenvEditor::loadFile(base_path('.env.example'));

        // Setup TELEGRAM_API_TOKEN
        if (! $example->has('TELEGRAM_API_TOKEN')) {
            $example->set('TELEGRAM_API_TOKEN');
        }

        // Setup TELEGRAM_WEBHOOK_SECRET
        if (! $example->has('TELEGRAM_WEBHOOK_SECRET')) {
            $example->set('TELEGRAM_WEBHOOK_SECRET');
        }

        $example->save();
    }

}
