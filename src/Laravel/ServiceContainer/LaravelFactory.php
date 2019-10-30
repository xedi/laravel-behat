<?php
namespace Xedi\Behat\Laravel\ServiceContainer;

use Xedi\Behat\ServiceContainer\Factory as BaseFactory;

/**
 * Laravel Factory
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class LaravelFactory extends BaseFactory
{
    /**
     * Gets the name of the driver being configured.
     *
     * This will be the key of the configuration for the driver.
     *
     * @return string
     */
    public function getDriverName()
    {
        return 'laravel';
    }

    /**
     * Gets the name of the container being configured.
     *
     * @return string
     */
    public function getDriverReference()
    {
        return 'laravel.app';
    }
}
