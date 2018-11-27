<?php

namespace Xedi\Behat\Lumen\ServiceContainer;

use Xedi\Behat\ServiceContainer\Booter as BaseBooter;

class LumenBooter extends BaseBooter
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

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->make('Illuminate\Http\Request')->capture();

        return $app;
    }
}
