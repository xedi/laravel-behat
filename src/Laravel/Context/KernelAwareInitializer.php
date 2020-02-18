<?php
namespace Xedi\Behat\Laravel\Context;

use Xedi\Behat\Context\KernelAwareContext;
use Xedi\Behat\Context\KernelAwareInitializer as BaseInitializer;
use Xedi\Behat\Laravel\ServiceContainer\LaravelBooter;

/**
 * Initialize the App Container
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class KernelAwareInitializer extends BaseInitializer
{
    /**
     * After each scenario, reboot the kernel.
     *
     * @return void
     */
    public function rebootKernel()
    {
        if ($this->context instanceof KernelAwareContext) {
            $this->kernel->flush();

            $laravel = new LaravelBooter(
                $this->kernel->basePath(),
                $this->kernel->environmentFile()
            );

            $this->context->getSession('laravel')
                ->getDriver()
                ->reboot($this->kernel = $laravel->boot());

            $this->setAppOnContext();
        }
    }
}
