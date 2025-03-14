<?php

declare(strict_types=1);

use Crud\Exception\CircularDependencyException;
use Crud\Exception\IllegalTemplatePathException;
use Crud\Exception\MissingDependencyInjectionParameterException;
use Crud\Exception\TemplateNotFoundException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\DependencyInjection\Container;

class ContainerTest extends TestCase
{
    #[DataProvider('provideTestContainerData')]
    final public function testContainer(string $serviceClass): void
    {
        // Initiate new container instance with parameters.
        $container = static::getContainer( withParameters: true);

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
        } else {
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

    final public function testWithConnectionAndTemplateServices(): void
    {
        $parameters = [
            'dsn' => 'mysql:host=localhost;dbname=crud_operation_test',
            'username' => 'root',
            'password' => 'root123',
            'templatePath' => __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
        ];

        $container = new Container($parameters);

        $this->assertFalse($container->has(Connection::class));
        $this->assertFalse($container->has(Template::class));

        $connectionService = $container->get(Connection::class);
        $this->assertInstanceOf(Connection::class, $connectionService);

        $templateService = $container->get(Template::class);
        $this->assertInstanceOf(Template::class, $templateService);
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
    public function __construct()
    {
    }
}

readonly class ServiceWithNoDependenciesAndNoConstruct
{
}

readonly class ServiceWithSingleDependency
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependencies
    ) {
    }
}

readonly class ServiceWithMultipleDependencies
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private ServiceWithNoDependencies $serviceWithNoDependenciesSecond,
    ) {
    }
}

readonly class ServiceWithMultipleDependantDependencies
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependenciesFirst,
        private ServiceWithSingleDependency $serviceWithSingleDependency,
        private ServiceWithMultipleDependencies $serviceWithMultipleDependencies,
    ) {
    }
}
abstract readonly class AbstractServiceWithSingleDependency
{
    public function __construct(
        private ServiceWithNoDependencies $serviceWithNoDependencies
    ) {
    }
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
    ) {
    }
}

readonly class ServiceWithMultipleParameterDependencies
{
    public function __construct(
        private string $stringParameter,
        private int $integerParameter,
        private bool $booleanParameter,
    ) {
    }
}

readonly class ServiceWithSingleDependencyAndParameterDependency
{
    public function __construct(
        private ServiceWithMultipleDependenciesExtendingAbstractService $serviceWithMultipleDependenciesExtendingAbstractService,
        private string $stringParameter,
    ) {
    }
}

readonly class ServiceWithMultipleDependenciesAndParameterDependencies
{
    public function __construct(
        private ServiceWithMultipleDependenciesExtendingAbstractService $serviceWithMultipleDependenciesExtendingAbstractService,
        private ServiceWithMultipleParameterDependencies $serviceWithMultipleParameterDependencies,
        private string $stringParameter,
        private bool $booleanParameter,
        private int $integerParameter,
    ) {
    }
}

readonly class ServiceWithCircularDependencies
{
    public function __construct(
        private ServiceWithCircularDependencies $serviceWithCircularDependencies,
    ) {
    }
}

readonly class ServiceWithCircularDependantDependenciesAndMissingParameters
{
    public function __construct(
        private ServiceWithCircularDependencies $serviceWithCircularDependencies,
        private string $stringParameter,
    ) {
    }
}

class Connection
{

    private string $dsn;
    private string $username;
    private string $password;

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;

    }

    public function connect(): PDO
    {
        return new PDO($this->dsn, $this->username, $this->password);
    }
}

readonly class Template
{
    public function __construct(private string $templatePath)
    {

    }

    public function render(string $template, array $parameters = []): string
    {
        if (str_starts_with($template, '.') || str_starts_with($template, '/')) {
            throw new IllegalTemplatePathException('No hola for you');
        }

        $templatePath = $this->templatePath . $template;

        if (file_exists($templatePath)) {
            extract($parameters);
            ob_start();
            include $templatePath;
            return ob_get_clean();
        } else {
            throw new TemplateNotFoundException('template not found: ' . $templatePath);
        }
    }
}
