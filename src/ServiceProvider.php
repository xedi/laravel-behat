<?php

namespace Xedi\Behat\Laravel;

use Xedi\Behat\Laravel\Commands\MakeDotEnvCommand;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    public function boot()
    {
        $this->commands([
            MakeDotEnvCommand::class,
        ]);
    }

    // public function register()
    // {
    //     $this->mergeConfigFrom(
    //         __DIR__ . '/../config/filesystem.php', 'filesystem'
    //     );
    // }
}
