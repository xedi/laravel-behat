<?php
namespace Xedi\Behat\Context;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * DatabaseTransactions Concern
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
trait DatabaseTransactions
{
    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }

    /**
     * Begin a database transaction.
     *
     * @BeforeScenario
     *
     * @return void
     */
    public function beginTransaction()
    {
        foreach ($this->connectionsToTransact() as $name) {
            DB::connection($name)->beginTransaction();
        }
    }

    /**
     * Roll it back after the scenario.
     *
     * @AfterScenario
     *
     * @return void
     */
    public function rollback()
    {
        foreach ($this->connectionsToTransact() as $name) {
            DB::connection($name)->rollBack();
        }
        Cache::flush();
    }
}
