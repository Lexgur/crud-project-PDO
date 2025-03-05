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