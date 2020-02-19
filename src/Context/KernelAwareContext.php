<?php
namespace Xedi\Behat\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * KernelAwareContext interface
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
interface KernelAwareContext extends Context
{
    /**
     * Set the kernel instance on the context.
     *
     * @param HttpKernelInterface $kernel Application Kernal
     *
     * @return mixed
     */
    public function setApp(HttpKernelInterface $kernel);

    /**
     * Returns the specified session or active session
     *
     * @param string|null $name name of the session
     *
     * @return mixed
     */
    public function getSession($name = null);
}
