<?php

namespace Xedi\Behat\Laravel\ServiceContainer;

use Xedi\Behat\Context\Argument\ArgumentResolver;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Xedi\Behat\ServiceContainer\Extension as BaseExtension;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

class LaravelExtension extends BaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'laravel';
    }


    public function getFactory()
    {
        return new LaravelFactory();
    }

    /**
     * {@inheritdoc}
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
     * @param ContainerBuilder $container
     * @param array            $config
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
     * @param ContainerBuilder    $container
     * @param HttpKernelInterface $app
     */
    private function loadInitializer(ContainerBuilder $container, $app)
    {
        $definition = new Definition('Xedi\Behat\Context\KernelAwareInitializer', [$app]);

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);

        $container->setDefinition('laravel.initializer', $definition);
    }

    /**
     * Load argument resolver
     *
     * @param  ContainerBuilder $container
     * @param  Application $app
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
