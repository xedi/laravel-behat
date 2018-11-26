<?php

namespace Xedi\Behat\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeDotEnvCommand extends Command
{
    protected $signature = 'make:behat-env';

    protected $description = 'Make the Behat DotEnv file';

    public function handle()
    {
        dd(config('filesystems'));
    }
}
