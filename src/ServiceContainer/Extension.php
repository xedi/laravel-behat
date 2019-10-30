<?php
namespace Xedi\Behat\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionContract;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Provides framework functionalities to Behat
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
abstract class Extension implements ExtensionContract
{
    /**
     * Returns the extension config key.
     *
     * @return string
     */
    abstract public function getConfigKey();

    /**
     * Returns the factory
     *
     * @return LaravelFactory
     */
    abstract public function getFactory();

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container App Container
     * @param array            $config    App Configuration
     *
     * @return void
     */
    abstract public function load(ContainerBuilder $container, array $config);

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager Resolved ExtensionManager
     *
     * @return void
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        if (null !== $minkExtension = $extensionManager->getExtension('mink')) {
            $minkExtension->registerDriverFactory($this->getFactory());
        }
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container Resolved instance of a ContainerBuilder
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        // noop
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder a ArrayNodeDefinition builder
     *
     * @return void
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('bootstrap_path')
                    ->defaultValue('bootstrap/app.php')
                ->end()
                ->scalarNode('env_path')
                    ->defaultValue('.env.behat');
    }
}
