<?php

namespace Xedi\Behat\Laravel;

use Xedi\Behat\Laravel\Commands\MakeBehatYAMLCommand;
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
