<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Crud\DependencyInjection\Container;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container(); // Initialize the container
    }

    public function testHasWhenServiceIsNotRegistered(): void
    {
        // Test for a service that has not been registered
        $this->assertFalse($this->container->has('NonExistentService'));
    }

    public function testHasWhenServiceIsRegistered(): void
    {
        $this->container->get('SomeService');

        $this->assertTrue($this->container->has('SomeService'));
    }
}

class SomeService
{

}
