<?php
namespace Xedi\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use RuntimeException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Abstract Factory
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
abstract class Factory implements DriverFactory
{
    /**
     * Gets the name of the driver being configured.
     *
     * This will be the key of the configuration for the driver.
     *
     * @return string
     */
    abstract public function getDriverName();

    /**
     * Gets the name of the container being configured.
     *
     * @return string
     */
    abstract public function getDriverReference();

    /**
     * Defines whether a session using this driver is eligible as default javascript session
     *
     * @return boolean
     */
    public function supportsJavascript()
    {
        return false;
    }

    /**
     * Setups configuration for the driver factory.
     *
     * @param ArrayNodeDefinition $builder Resolved ArrayNodeDefinition builder
     *
     * @return void
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        // noop
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
        $this->assertBrowserkitIsAvailable();

        return new Definition('Xedi\Behat\Driver\KernelDriver', [
            new Reference($this->getDriverReference()),
            '%mink.base_url%'
        ]);
    }

    /**
     * Ensure that BrowserKit is available.
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function assertBrowserkitIsAvailable()
    {
        if (! class_exists('Behat\Mink\Driver\BrowserKitDriver')) {
            $driver_name = $this->getDriverName();

            throw new RuntimeException(
                "Install MinkBrowserKitDriver in order to use the $driver_name driver."
            );
        }
    }
}
