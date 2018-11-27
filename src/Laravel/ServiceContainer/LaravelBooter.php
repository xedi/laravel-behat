<?php

namespace Xedi\Behat\Laravel\ServiceContainer;

use Xedi\Behat\ServiceContainer\Booter as BaseBooter;

class LaravelBooter extends BaseBooter
{
    /**
     * {@inheritdoc}
     */
    public function environmentFile()
    {
        return $this->environmentFile;
    }


    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $bootstrapPath = $this->basePath() . '/bootstrap/app.php';

        $this->assertBootstrapFileExists($bootstrapPath);

        $app = require $bootstrapPath;

        $app->loadEnvironmentFrom($this->environmentFile());

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->make('Illuminate\Http\Request')->capture();

        return $app;
    }
}
