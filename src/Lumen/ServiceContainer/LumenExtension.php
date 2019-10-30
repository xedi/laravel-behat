<?php

namespace Xedi\Behat\Lumen\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Xedi\Behat\Lumen\Context\ApplicationAwareInitializer;
use Xedi\Behat\ServiceContainer\Extension as BaseExtension;

/**
 * Provides framework functionalities to Behat
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class LumenExtension extends BaseExtension
{
    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'lumen';
    }

    /**
     * Returns the factory
     *
     * @return LaravelFactory
     */
    public function getFactory()
    {
        return new LumenFactory();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container App Container
     * @param array            $config    App Configuration
     *
     * @return void
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $app = $this->loadLumen($container, $config);

        $this->loadInitializer($container, $app);
    }

    /**
     * Boot up Lumen.
     *
     * @param ContainerBuilder $container Application Container
     * @param array            $config    Application configuration
     *
     * @internal
     *
     * @return mixed
     */
    private function loadLumen(ContainerBuilder $container, array $config)
    {
        $lumen = new LumenBooter($container->getParameter('paths.base'), $config['env_path']);

        $container->set('lumen.app', $app = $lumen->boot());

        return $app;
    }

    /**
     * Load the initializer.
     *
     * @param ContainerBuilder    $container Resolved ContainerBuilder instance
     * @param HttpKernelInterface $app       App Instance
     *
     * @internal
     *
     * @return void
     */
    private function loadInitializer(ContainerBuilder $container, $app)
    {
        $definition = new Definition(ApplicationAwareInitializer::class, [ $app ]);

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, [ 'priority' => 0 ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, [ 'priority' => 0 ]);

        $container->setDefinition('lumen.initializer', $definition);
    }
}
