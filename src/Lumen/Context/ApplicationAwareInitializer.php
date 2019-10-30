<?php
namespace Xedi\Behat\Lumen\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Illuminate\Support\Facades\Facade;
use Laravel\Lumen\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Xedi\Behat\Lumen\ServiceContainer\LumenBooter;

/**
 * ApplicationAware Initializer
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class ApplicationAwareInitializer implements EventSubscriberInterface, ContextInitializer
{
    /**
     * The app kernel.
     *
     * @var Application
     */
    private $app;

    /**
     * The Behat context.
     *
     * @var Context
     */
    private $context;

    /**
     * Construct the initializer.
     *
     * @param Application $app Application container
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
            ScenarioTested::AFTER => [ 'reboot', -15 ]
        ];
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context Behat context
     *
     * @return void
     */
    public function initializeContext(Context $context)
    {
        $this->context = $context;
        $this->setAppOnContext();
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
        if ($this->context instanceof ApplicationAwareContext) {
            $this->context->setApp($this->app);
        }
    }

    /**
     * After each scenario, reboot the kernel.
     *
     * @return void
     */
    public function reboot()
    {
        Facade::clearResolvedInstances();

        $lumen = new LumenBooter($this->app->basePath());
        $this->context->getSession('lumen')->getDriver()->reboot($this->app = $lumen->boot());
        $this->setAppOnContext();
    }
}
