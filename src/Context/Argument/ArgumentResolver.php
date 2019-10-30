<?php
namespace Xedi\Behat\Context\Argument;

use Behat\Behat\Context\Argument\ArgumentResolver;
use Illuminate\Container\Container as Application;
use ReflectionClass;

/**
 * Resolves arguments of context constructors
 *
 * @package Xedi\Behat
 * @author  Chris Smith <chris@xedi.com>
 */
class ArgumentResolver implements ArgumentResolver
{
    /**
     * Application Container
     *
     * @var Application
     */
    private $app;

    /**
     * Initialize the ArgumentResolver
     *
     * @param Application $app Application container
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Resolves context constructor arguments.
     *
     * @param ReflectionClass $classReflection Reflection of the class
     * @param array           $arguments       Provided arguments
     *
     * @return array
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $resolvedArguments = [];

        foreach ($arguments as $key => $argument) {
            $resolvedArguments[$key] = $this->resolveArgument($argument);
        }

        return $resolvedArguments;
    }

    /**
     * Resolve argument
     *
     * @param string $arg Argument to resolve
     *
     * @internal
     *
     * @return object|string
     */
    private function resolveArgument($arg)
    {
        if (substr($arg, 0, 1) === '@') {
            return $this->app->make(substr($arg, 1));
        }

        return $arg;
    }
}
