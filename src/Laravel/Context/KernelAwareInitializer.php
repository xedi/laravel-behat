<?php

namespace Xedi\Behat\Laravel\Context;

use Xedi\Behat\Context\KernalAwareContext;
use Xedi\Behat\Laravel\ServiceContainer\LaravelBooter;
use Xedi\Behat\Context\KernelAwareInitializer as BaseInitializer;

class KernelAwareInitializer extends BaseInitializer
{
    /**
     * After each scenario, reboot the kernel.
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
