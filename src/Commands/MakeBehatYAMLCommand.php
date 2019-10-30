<?php
namespace Xedi\Behat\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Make BehatYAML Command
 *
 * @package Xedit\Behat
 * @author  Chris Smith <chris@xedi.com>
 *
 * @deprecated 1.0.2 Migrating to vendor publishing
 */
class MakeBehatYAMLCommand extends Command
{
    /**
     * Command signature
     *
     * @var string
     */
    protected $signature = 'make:behat-yaml';

    /**
     * Command Description
     *
     * @var string
     */
    protected $description = 'Make the Behat YAML file';

    /**
     * Perform the commands function
     *
     * @return void
     */
    public function handle()
    {
        $template = $this->getStub();

        if ($this->confirm('Do you want to use a custom dotenv file?')) {
            $replacement = $this->ask('What is the name of your dotenv file?');

            $template = str_replace("# env_path: .env.behat", "env_path: $replacement", $template);
        }

        File::put(app()->basePath() . '/behat.yml', $template);
    }

    /**
     * Fetch the correct stub
     *
     * @return string
     */
    protected function getStub() : string
    {
        if (preg_match('/Lumen/', get_class(app()))) {
            $stub = '/stubs/lumen.behat.yml.stub';
        } else {
            $stub = '/stubs/laravel.behat.yml.stub';
        }

        return File::get(__DIR__ . $stub);
    }
}
