<?php

namespace Xedi\Behat\Laravel\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Xedi\Behat\Laravel\ServiceContainer\LaravelBooter;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KernelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{

    /**
     * The app kernel.
     *
     * @var HttpKernelInterface
     */
    private $kernel;

    /**
     * The Behat context.
     *
     * @var Context
     */
    private $context;

    /**
     * Construct the initializer.
     *
     * @param HttpKernelInterface $kernel
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScenarioTested::AFTER => ['rebootKernel', -15]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        $this->context = $context;

        $this->setAppOnContext($this->kernel);
    }

    /**
     * Set the app kernel to the feature context.
     */
    private function setAppOnContext()
    {
        if ($this->context instanceof KernelAwareContext) {
            $this->context->setApp($this->kernel);
        }
    }

    /**
     * After each scenario, reboot the kernel.
     */
    public function rebootKernel()
    {
        if ($this->context instanceof KernelAwareContext)
        {
            $this->kernel->flush();

            $laravel = new LaravelBooter($this->kernel->basePath(), $this->kernel->environmentFile());

            $this->context->getSession('laravel')->getDriver()->reboot($this->kernel = $laravel->boot());

            $this->setAppOnContext();
        }
    }

}
