<?php

namespace Xedi\Behat\Context;

use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * App Trait
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
trait App
{
    /**
     * The App Container
     *
     * @var HttpKernelInterface
     */
    protected $app;

    /**
     * Set the application.
     *
     * @param HttpKernelInterface $app App Container
     *
     * @return void
     */
    public function setApp(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    /**
     * Get the application.
     *
     * @return HttpKernelInterface
     */
    public function app()
    {
        return $this->app;
    }
}
