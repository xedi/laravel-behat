<?php

namespace Xedi\Behat\Laravel\ServiceContainer;

use Xedi\Behat\ServiceContainer\Factory as BaseFactory;

class LaravelFactory extends BaseFactory
{
    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return 'laravel';
    }

    public function getDriverReference()
    {
        return 'laravel.app';
    }
}
