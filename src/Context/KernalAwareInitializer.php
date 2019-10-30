<?php
namespace Xedi\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Initialize the App Container
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
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
     * After each scenario, reboot the kernel.
     *
     * @return void
     */
    abstract public function rebootKernel();

    /**
     * Construct the initializer.
     *
     * @param HttpKernelInterface $kernel Application Kernal
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ScenarioTested::AFTER => ['rebootKernel', -15]
        ];
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context Behat Context
     *
     * @return void
     */
    public function initializeContext(Context $context)
    {
        $this->context = $context;

        $this->setAppOnContext($this->kernel);
    }

    /**
     * Set the app kernel to the feature context.
     *
     * @internal
     *
     * @return void
     */
    private function setAppOnContext()
    {
        if ($this->context instanceof KernelAwareContext) {
            $this->context->setApp($this->kernel);
        }
    }

    /**
     * Get the registered Kernal
     *
     * @return HttpKernalInterface
     */
    protected function getKernal()
    {
        return $this->kernal;
    }

    /**
     * Get the registered Behat Context
     *
     * @return Context
     */
    protected function getContext()
    {
        return $this->context;
    }
}
