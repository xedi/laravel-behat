<?php

namespace Xedi\Behat\Lumen\Context;

use Laravel\Lumen\Application;
use Behat\Behat\Context\Context;
use Illuminate\Support\Facades\Facade;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Xedi\Behat\Lumen\ServiceContainer\Booter as LumenBooter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ScenarioTested::AFTER => [ 'reboot', -15 ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        $this->context = $context;
        $this->setAppOnContext();
    }

    /**
     * Set the app kernel to the feature context.
     */
    private function setAppOnContext()
    {
        if ($this->context instanceof ApplicationAwareContext) {
            $this->context->setApp($this->app);
        }
    }

    /**
     * After each scenario, reboot the kernel.
     */
    public function reboot()
    {
        Facade::clearResolvedInstances();

        $lumen = new LumenBooter($this->app->basePath());
        $this->context->getSession('lumen')->getDriver()->reboot($this->app = $lumen->boot());
        $this->setAppOnContext();
    }
}
