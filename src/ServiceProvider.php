<?php
namespace Xedi\Behat;

use Illuminate\Support\ServiceProvider as BaseProvider;
use Xedi\Behat\Commands\MakeBehatYAMLCommand;

/**
 * Behat ServiceProvider
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class ServiceProvider extends BaseProvider
{
    /**
     * Boot the Service into the App Container
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            MakeBehatYAMLCommand::class,
        ]);
    }
}
