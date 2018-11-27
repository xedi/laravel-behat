<?php

namespace Xedi\Behat\ServiceContainer;

use RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

abstract class Factory implements DriverFactory
{

    /**
     * {@inheritdoc}
     */
    abstract public function getDriverName();

    abstract public function getDriverReference();

    /**
     * {@inheritdoc}
     */
    public function supportsJavascript()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        //
    }

    /**
     * {@inheritdoc}
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
     */
    protected function assertBrowserkitIsAvailable()
    {
        if ( ! class_exists('Behat\Mink\Driver\BrowserKitDriver')) {
            $driver_name = $this->getDriverName();

            throw new RuntimeException(
                "Install MinkBrowserKitDriver in order to use the $driver_name driver."
            );
        }
    }
}
