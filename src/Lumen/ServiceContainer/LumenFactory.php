<?php

namespace Xedi\Behat\Lumen\ServiceContainer;

use Xedi\Behat\ServiceContainer\Factory as BaseFactory;

class LumenFactory extends BaseFactory
{
    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return 'lumen';
    }

    public function getDriverReference()
    {
        return 'lumen.app';
    }
}
