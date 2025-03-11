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

    public function testGetServiceWithMultipleDependencies(): void
    {
        $service = $this->container->get('ServiceWithMultipleDependencies');

        $this->assertInstanceOf(ServiceWithMultipleDependencies::class, $service);
        $this->assertTrue($service->isInitialized());
    }

    public function testGetServiceWithMultipleDependantDependencies(): void
    {
        $service = $this->container->get('ServiceWithMultipleDependantDependencies');

        $this->assertInstanceOf(ServiceWithMultipleDependantDependencies::class, $service);
        $this->assertTrue($service->isInitialized());
    }

    public function testGetServiceWithMultipleDependenciesExtendingAbstractService(): void
    {
        $service = $this->container->get('ServiceWithMultipleDependenciesExtendingAbstractService');

        $this->assertInstanceOf(ServiceWithMultipleDependenciesExtendingAbstractService::class, $service);
        $this->assertTrue($service->isInitialized());

        print_r($service);
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

class ServiceWithMultipleDependencies {
    public function __construct(
        private readonly ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private readonly ServiceWithNoDependencies $serviceWithNoDependenciesSecond,
    ) {
    }

    public function isInitialized(): bool
    {
        return true;
    }
}

class ServiceWithMultipleDependantDependencies {
    public function __construct(
        private readonly ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private readonly ServiceWithSingleDependency $serviceWithSingleDependency,
        private readonly ServiceWithMultipleDependencies $serviceWithMultipleDependencies,
    ) {
    }

    public function isInitialized(): bool
    {
        return true;
    }
}
abstract class AbstractServiceWithSingleDependency {
    public function __construct(
        private readonly ServiceWithNoDependencies $serviceWithNoDependencies
    ) {
    }

    public function isInitialized(): bool
    {
        return true;
    }
}

class ServiceWithMultipleDependenciesExtendingAbstractService extends AbstractServiceWithSingleDependency {
    public function __construct(
        private readonly ServiceWithNoDependencies $serviceWithNoDependencies,
        private readonly ServiceWithSingleDependency $serviceWithSingleDependency,
        private readonly ServiceWithMultipleDependencies $serviceWithMultipleDependencies,
        private readonly ServiceWithMultipleDependantDependencies $serviceWithMultipleDependenciesSecond,
    ) {
        parent::__construct($serviceWithNoDependencies);
    }

    public function isInitialized(): bool
    {
        return true;
    }
}
