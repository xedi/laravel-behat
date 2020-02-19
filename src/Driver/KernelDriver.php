<?php
namespace Xedi\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Kernel Driver
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class KernelDriver extends BrowserKitDriver
{
    /**
     * Create a new KernelDriver.
     *
     * @param HttpKernelInterface $app     Application container
     * @param string|null         $baseUrl Base URL of the application
     */
    public function __construct(HttpKernelInterface $app, $baseUrl = null)
    {
        parent::__construct(new Client($app), $baseUrl);
    }

    /**
     * Refresh the driver.
     *
     * @param HttpKernelInterface $app Application container
     *
     * @return KernelDriver
     */
    public function reboot($app)
    {
        return $this->__construct($app);
    }
}
