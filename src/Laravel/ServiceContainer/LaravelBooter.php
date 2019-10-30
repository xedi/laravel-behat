<?php
namespace Xedi\Behat\Laravel\ServiceContainer;

use Xedi\Behat\ServiceContainer\Booter as BaseBooter;

/**
 * Boots a Laravel application
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class LaravelBooter extends BaseBooter
{
    /**
     * Get the application's environment file.
     *
     * @return string
     */
    public function environmentFile()
    {
        return $this->environmentFile;
    }

    /**
     * Boot the app.
     *
     * @return mixed
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
