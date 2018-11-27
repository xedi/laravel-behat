<?php

namespace Xedi\Behat\Lumen\ServiceContainer;

use Xedi\Behat\Context\Argument\ArgumentResolver;
use Symfony\Component\DependencyInjection\Reference;
use Xedi\Behat\Lumen\Context\KernelAwareInitializer;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Xedi\Behat\ServiceContainer\Extension as BaseExtension;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

class LumenExtension extends BaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'lumen';
    }


    public function getFactory()
    {
        return new LumenFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $app = $this->loadLumen($container, $config);

        $this->loadInitializer($container, $app);
        $this->loadArgumentResolver($container, $app);
    }

    /**
     * Boot up Lumen.
     *
     * @param ContainerBuilder $container
     * @param array            $config
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
     * @param ContainerBuilder    $container
     * @param HttpKernelInterface $app
     */
    private function loadInitializer(ContainerBuilder $container, $app)
    {
        $definition = new Definition(AwareInitializer::class, [ $app ]);

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, [ 'priority' => 0 ]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, [ 'priority' => 0 ]);

        $container->setDefinition('lumen.initializer', $definition);
    }

    /**
     * Load argument resolver
     *
     * @param  ContainerBuilder $container
     * @param  Application $app
     */
    private function loadArgumentResolver(ContainerBuilder $container, $app)
    {
        $definition = new Definition(ArgumentResolver::class, [
            new Reference('lumen.app')
        ]);
        $definition->addTag(ContextExtension::ARGUMENT_RESOLVER_TAG, [ 'priority' => 0 ]);
        $container->setDefinition('lumen.context.argument.service_resolver', $definition);
    }
}
