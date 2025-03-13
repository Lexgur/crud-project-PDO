<?php

declare(strict_types=1);

use Crud\Exception\CircularDependencyException;
use Crud\Exception\MissingDependencyInjectionParameterException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\DependencyInjection\Container;

class ContainerTest extends TestCase
{
    #[DataProvider('provideTestContainerData')]
    final public function testContainer(string $serviceClass): void
    {
        // Initiate new container instance with parameters.
        $container = static::getContainer(withParameters: true);

        // Test if service is not yet initiated.
        $this->assertFalse($container->has($serviceClass));
        // Test if service is initiated successfully - instance exists.
        $this->assertInstanceOf($serviceClass, $container->get($serviceClass));
        // Test if service is already initiated.
        $this->assertTrue($container->has($serviceClass));
    }

    public static function provideTestContainerData(): array
    {
        return [
            [ServiceWithNoDependencies::class],
            [ServiceWithNoDependenciesAndNoConstruct::class],
            [ServiceWithSingleDependency::class],
            [ServiceWithMultipleDependencies::class],
            [ServiceWithMultipleDependantDependencies::class],
            [ServiceWithMultipleDependenciesExtendingAbstractService::class],
            [ServiceWithSingleParameterDependency::class],
            [ServiceWithMultipleParameterDependencies::class],
            [ServiceWithSingleDependencyAndParameterDependency::class],
            [ServiceWithMultipleDependenciesAndParameterDependencies::class],
        ];
    }

    #[DataProvider('provideTestContainerWithoutRequiredParametersData')]
    final public function testContainerWithoutRequiredParameters(string $serviceClass, bool $expectedException): void
    {
        // Initiate new container instance without parameters.
        $container = static::getContainer();
        // Test if service is not yet initialized.
        $this->assertFalse($container->has($serviceClass));

        if ($expectedException) {
            // Service should throw out MissingDependencyInjectionParameterException.
            $this->expectException(MissingDependencyInjectionParameterException::class);
            $container->get($serviceClass);
        }
        else {
            // Test if service is initiated successfully - instance exists.
            $this->assertInstanceOf($serviceClass, $container->get($serviceClass));
            // Test if service is already initiated.
            $this->assertTrue($container->has($serviceClass));
        }
    }

    public static function provideTestContainerWithoutRequiredParametersData(): array
    {
        return [
            [ServiceWithNoDependencies::class, false],
            [ServiceWithNoDependenciesAndNoConstruct::class, false],
            [ServiceWithSingleDependency::class, false],
            [ServiceWithMultipleDependencies::class, false],
            [ServiceWithMultipleDependantDependencies::class, false],
            [ServiceWithMultipleDependenciesExtendingAbstractService::class, false],
            [ServiceWithSingleParameterDependency::class, true],
            [ServiceWithMultipleParameterDependencies::class, true],
            [ServiceWithSingleDependencyAndParameterDependency::class, true],
            [ServiceWithMultipleDependenciesAndParameterDependencies::class, true],
        ];
    }

    #[DataProvider('provideTestCircularDependencyInServiceContainer')]
    final public function testCircularDependencyInServiceContainer(string $serviceClass, bool $containerWithParameters): void
    {
        // Initiate new container instance with parameters.
        $container = static::getContainer(withParameters: $containerWithParameters);

        // Test if service is not yet initialized.
        $this->assertFalse($container->has(ServiceWithCircularDependencies::class));
        // Service should throw out CircularDependencyException.
        $this->expectException(CircularDependencyException::class);
        $container->get($serviceClass);
    }

    public static function provideTestCircularDependencyInServiceContainer(): array
    {
        return [
            [ServiceWithCircularDependencies::class, true],
            [ServiceWithCircularDependantDependenciesAndMissingParameters::class, true],
            [ServiceWithCircularDependantDependenciesAndMissingParameters::class, false]
        ];
    }

    private static function getContainer(bool $withParameters = false): Container
    {
        if ($withParameters === false) {
            return new Container();
        }

        return new Container([
            'stringParameter' => 'someString',
            'integerParameter' => 123,
            'booleanParameter' => true,
        ]);
    }
}

readonly class ServiceWithNoDependencies
{
    public function __construct() {}
}

readonly class ServiceWithNoDependenciesAndNoConstruct {}

readonly class ServiceWithSingleDependency
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependencies
    ) {}
}

readonly class ServiceWithMultipleDependencies
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private ServiceWithNoDependencies $serviceWithNoDependenciesSecond,
    ) {}
}

readonly class ServiceWithMultipleDependantDependencies
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private ServiceWithSingleDependency $serviceWithSingleDependency,
        private ServiceWithMultipleDependencies $serviceWithMultipleDependencies,
    ) {}
}
abstract readonly class AbstractServiceWithSingleDependency
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependencies
    ) {}
}

readonly class ServiceWithMultipleDependenciesExtendingAbstractService extends AbstractServiceWithSingleDependency
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependencies,
        private ServiceWithSingleDependency $serviceWithSingleDependency,
        private ServiceWithMultipleDependencies $serviceWithMultipleDependencies,
        private ServiceWithMultipleDependantDependencies $serviceWithMultipleDependenciesSecond,
    ) {
        parent::__construct($serviceWithNoDependencies);
    }
}

readonly class ServiceWithSingleParameterDependency
{
    public function __construct(
        private string $stringParameter,
    ) {}
}

readonly class ServiceWithMultipleParameterDependencies
{
    public function __construct(
        private string $stringParameter,
        private int $integerParameter,
        private bool $booleanParameter,
    ) {}
}

readonly class ServiceWithSingleDependencyAndParameterDependency
{
    public function __construct(
        private ServiceWithMultipleDependenciesExtendingAbstractService $serviceWithMultipleDependenciesExtendingAbstractService,
        private string $stringParameter,
    ) {}
}

readonly class ServiceWithMultipleDependenciesAndParameterDependencies
{
    public function __construct(
        private ServiceWithMultipleDependenciesExtendingAbstractService $serviceWithMultipleDependenciesExtendingAbstractService,
        private ServiceWithMultipleParameterDependencies $serviceWithMultipleParameterDependencies,
        private string $stringParameter,
        private bool $booleanParameter,
        private int $integerParameter,
    ) {}
}

readonly class ServiceWithCircularDependencies
{
    public function __construct(
        private ServiceWithCircularDependencies $serviceWithCircularDependencies,
    ) {}
}

readonly class ServiceWithCircularDependantDependenciesAndMissingParameters
{
    public function __construct(
        private ServiceWithCircularDependencies $serviceWithCircularDependencies,
        private string $stringParameter,
    ) {}
}