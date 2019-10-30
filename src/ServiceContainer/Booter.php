<?php
namespace Xedi\Behat\ServiceContainer;

use RuntimeException;

/**
 * Abstract application booter
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
abstract class Booter
{
    /**
     * The base path for the application.
     *
     * @var string
     */
    private $basePath;

    /**
     * The application's environment file.
     *
     * @var string
     */
    private $environmentFile;

    /**
     * Get the application's environment file.
     *
     * @return string
     */
    abstract public function environmentFile();

    /**
     * Boot the app.
     *
     * @return mixed
     */
    abstract public function boot();

    /**
     * Create a new Laravel booter instance.
     *
     * @param string $basePath        Path to the base of the app
     * @param string $environmentFile Name of the environment file
     */
    public function __construct($basePath, $environmentFile = '.env.behat')
    {
        $this->basePath = $basePath;
        $this->environmentFile = $environmentFile;
    }

    /**
     * Get the application's base path.
     *
     * @return string
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Ensure that the provided bootstrap path exists.
     *
     * @param string $bootstrapPath Path to the applications bootstrap file
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function assertBootstrapFileExists($bootstrapPath)
    {
        if (! file_exists($bootstrapPath)) {
            throw new RuntimeException('Could not locate the path to the bootstrap file.');
        }
    }
}
