<?php
namespace Xedi\Behat\Lumen\Context;

use Xedi\Behat\Context\KernelAwareContext;
use Xedi\Behat\Context\KernelAwareInitializer as BaseInitializer;
use Xedi\Behat\Lumen\ServiceContainer\LumenBooter;

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
