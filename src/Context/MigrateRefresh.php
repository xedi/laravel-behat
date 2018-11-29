<?php

namespace Xedi\Behat\Context;

use Illuminate\Support\Facades\Artisan;

trait MigrateRefresh
{
    /**
     * Migrate the database before each scenario.
     *
     * @beforeScenario
     */
    public function migrate()
    {
        Artisan::call('migrate:refresh');
    }
}
