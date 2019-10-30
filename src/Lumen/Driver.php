<?php

namespace Xedi\Behat\Lumen;

use Behat\Mink\Driver\BrowserKitDriver;
use Laravel\Lumen\Application;

/**
 * Symfony2 BrowserKit driver.
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class Driver extends BrowserKitDriver
{
    /**
     * Initialize Driver
     *
     * @param Application $app      Application container
     * @param string|null $base_url Base URL of the application
     */
    public function __construct(Application $app, $base_url = null)
    {
        parent::__construct(new Client($app), $base_url);
    }

    /**
     * Reboot the driver
     *
     * @param Application $app Application container
     *
     * @return self
     */
    public function reboot($app)
    {
        return self::__construct($app);
    }
}
