<?php

namespace Xedi\Behat\Lumen;

use Behat\Mink\Driver\BrowserKitDriver;
use Laravel\Lumen\Application;

class Driver extends BrowserKitDriver
{
    public function __construct(Application $app, $base_url = null)
    {
        parent::__construct(new Client($app), $base);
    }

    public function reboot($app)
    {
        return self::__construct($app);
    }
}
