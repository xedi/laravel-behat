<?php

namespace Xedi\Behat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeBehatYAMLCommand extends Command
{
    protected $signature = 'make:behat-yaml';

    protected $description = 'Make the Behat YAML file';

    public function handle()
    {
        $template = $this->getStub();

        if ($this->confirm('Do you want to use a custom dotenv file?')) {
            $replacement = $this->ask('What is the name of your dotenv file?');

            $template = str_replace("# env_path: .env.behat", "env_path: $replacement", $template);
        }

        File::put(app()->basePath() . '/behat.yml', $template);
    }

    protected function getStub() : string
    {
        return File::get(__DIR__ . '/stubs/behat.yml.stub');
    }
}
