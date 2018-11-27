<?php

namespace spec\Xedi\Behat\ServiceContainer;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class LaravelBooterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(__DIR__, '.env.foo');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Xedi\Behat\Laravel\ServiceContainer\LaravelBooter');
    }

    function it_knows_the_base_path()
    {
        $this->basePath()->shouldBe(__DIR__);
    }

    function it_knows_the_environment_file()
    {
        $this->environmentFile()->shouldBe('.env.foo');
    }

    function it_takes_exception_with_a_missing_bootstrap_file()
    {
        $this->shouldThrow('RuntimeException')->duringBoot();
    }

}
