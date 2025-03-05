<?php

declare(strict_types=1);

use Crud\DependencyInjection\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    #[DataProvider('serviceClassProvider')]
    public function testContainer(string $serviceClass): void
    {
        $container = new Container();
        $this->assertTrue($container->has($serviceClass));

        $service = $container->get($serviceClass);

        $this->assertInstanceOf($serviceClass, $service);
        $this->assertTrue($service->isInitialized());
    }

    public static function serviceClassProvider(): array
    {
        return [
            [ServiceWithNoDependencies::class],
            [ServiceWithSingleDependency::class],
            [ServiceWithMultipleDependencies::class],
            [ServiceWithMultipleDependantDependencies::class],
            [ServiceWithMultipleDependenciesExtendingAbstractService::class],
        ];
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

readonly class ServiceWithSingleDependency
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependencies
    )
    {

    }

    public function isInitialized(): bool
    {
        return true;
    }
}

readonly class ServiceWithMultipleDependencies
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private ServiceWithNoDependencies $serviceWithNoDependenciesSecond,
    )
    {
    }

    public function isInitialized(): bool
    {
        return true;
    }
}

readonly class ServiceWithMultipleDependantDependencies
{
    public function __construct(
        private ServiceWithNoDependencies       $serviceWithNoDependenciesFirst,
        private ServiceWithSingleDependency     $serviceWithSingleDependency,
        private ServiceWithMultipleDependencies $serviceWithMultipleDependencies,
    )
    {
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
