<?php
namespace Xedi\Behat\Context;

use Illuminate\Support\Facades\Artisan;

/**
 * Migrate Fresh Concern
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
trait Migratefresh
{
    /**
     * Migrate the database before each scenario.
     *
     * @beforeScenario
     *
     * @return void
     */
    public function migrate()
    {
        Artisan::call('migrate:fresh');
    }
}
