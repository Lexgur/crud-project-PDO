<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Crud\DependencyInjection\Container;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testHasWhenServiceIsRegistered(): void
    {
        $this->container->get('ServiceWithNoDependencies');

        $this->assertTrue($this->container->has('ServiceWithNoDependencies'));
    }

    public function testGetServiceWithNoDependencies(): void
    {
        $service = $this->container->get('ServiceWithNoDependencies');

        $this->assertInstanceOf(ServiceWithNoDependencies::class, $service);
        $this->assertTrue($service->isInitialized());
    }

    public function testGetServiceWithSingleDependency(): void
    {
        $service = $this->container->get('ServiceWithSingleDependency');

        $this->assertInstanceOf(ServiceWithSingleDependency::class, $service);
        $this->assertTrue($service->isInitialized());
    }
}

class ServiceWithNoDependencies
{
    public function __construct()
    {
    }
    public function isInitialized(): bool
    {
        return true;
    }
}
//TODO ServiceWithSIngleDependency neveikia
class ServiceWithSingleDependency
{
    public function __construct(
        private readonly ServiceWithNoDependencies $serviceWithNoDependencies
    ) {
    }

    public function isInitialized(): bool
    {
        return true;
    }
}
