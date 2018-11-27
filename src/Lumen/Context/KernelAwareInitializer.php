<?php

namespace Xedi\Behat\Lumen\Context;

use Xedi\Behat\Context\KernalAwareContext;
use Xedi\Behat\Lumen\ServiceContainer\LumenBooter;
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

            $lumen = new LumenBooter(
                $this->kernel->basePath(),
                $this->kernel->environmentFile()
            );

            $this->context->getSession('lumen')
                ->getDriver()
                ->reboot($this->kernel = $lumen->boot());

            $this->setAppOnContext();
        }
    }
}
