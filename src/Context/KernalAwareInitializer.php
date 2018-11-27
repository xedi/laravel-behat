<?php

namespace Xedi\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class KernelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{

    /**
     * The app kernel.
     *
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * The Behat context.
     *
     * @var Context
     */
    protected $context;

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

    protected function getKernal()
    {
        return $this->kernal;
    }

    protected function getContext()
    {
        return $this->context;
    }

    /**
     * After each scenario, reboot the kernel.
     */
    abstract public function rebootKernel();

}
