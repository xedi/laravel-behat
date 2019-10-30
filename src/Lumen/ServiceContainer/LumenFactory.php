<?php
namespace Xedi\Behat\Lumen\ServiceContainer;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Xedi\Behat\Lumen\Driver as LumenDriver;
use Xedi\Behat\ServiceContainer\Factory as BaseFactory;

/**
 * Lumen Factory
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class LumenFactory extends BaseFactory
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
        return 'lumen';
    }

    /**
     * Gets the name of the container being configured.
     *
     * @return string
     */
    public function getDriverReference()
    {
        return 'lumen.app';
    }

    /**
     * Builds the service definition for the driver.
     *
     * @param array $config Driver configuration
     *
     * @return Definition
     */
    public function buildDriver(array $config)
    {
        $this->assertBrowserKitIsAvailable();

        return new Definition(
            LumenDriver::class,
            [
                new Reference('lumen.app'),
                '%mink.base_url%'
            ]
        );
    }
}
