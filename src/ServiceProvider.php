<?php

namespace Xedi\Behat;

use Xedi\Behat\Commands\MakeBehatYAMLCommand;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    public function boot()
    {
        $this->commands([
            MakeBehatYAMLCommand::class,
        ]);
    }
}
