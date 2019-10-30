<?php
namespace Xedi\Behat\Laravel\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Xedi\Behat\Context\Argument\ArgumentResolver;
use Xedi\Behat\Laravel\Context\KernelAwareInitializer;
use Xedi\Behat\ServiceContainer\Extension as BaseExtension;

/**
 * Provides framework functionalities to Behat
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class LaravelExtension extends BaseExtension
{
    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'laravel';
    }

    /**
     * Returns the factory
     *
     * @return LaravelFactory
     */
    public function getFactory()
    {
        return new LaravelFactory();
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
        $app = $this->loadLaravel($container, $config);

        $this->loadInitializer($container, $app);
        $this->loadLaravelArgumentResolver($container, $app);
    }

    /**
     * Boot up Laravel.
     *
     * @param ContainerBuilder $container Resolved ContainerBuilder instance
     * @param array            $config    App Configuration
     *
     * @internal
     *
     * @return mixed
     */
    private function loadLaravel(ContainerBuilder $container, array $config)
    {
        $laravel = new LaravelBooter($container->getParameter('paths.base'), $config['env_path']);

        $container->set('laravel.app', $app = $laravel->boot());

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
        $definition = new Definition(KernelAwareInitializer::class, [$app]);

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);

        $container->setDefinition('laravel.initializer', $definition);
    }

    /**
     * Load argument resolver
     *
     * @param ContainerBuilder $container Resolved ContainerBuilder instance
     * @param Application      $app       App Instance
     *
     * @internal
     *
     * @return void
     */
    private function loadLaravelArgumentResolver(ContainerBuilder $container, $app)
    {
        $definition = new Definition(ArgumentResolver::class, [
            new Reference('laravel.app')
        ]);
        $definition->addTag(ContextExtension::ARGUMENT_RESOLVER_TAG, ['priority' => 0]);
        $container->setDefinition('laravel.context.argument.service_resolver', $definition);
    }
}
