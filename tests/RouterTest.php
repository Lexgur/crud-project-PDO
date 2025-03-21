<?php

declare(strict_types=1);

use Crud\Controller\CreateUser;
use Crud\Controller\DeleteUser;
use Crud\Controller\UpdateUser;
use Crud\Controller\ViewUser;
use Crud\Exception\IncorrectRoutePathException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\Core\Router;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->router->registerControllers(__DIR__ . '/../src/Controller');
    }
    #[DataProvider('provideTestGetControllerData')]
    final public function testGetController(string $routePath, string $expectedController): void
    {
        $controller = $this->router->getController($routePath);
        $this->assertInstanceOf($expectedController, $controller);
    }
    final public function testIncorrectPathThrowsIncorrectRoutePathException(): void
    {
        $this->expectException(IncorrectRoutePathException::class);

        $this->router->getController('/incorrect');
    }

    final public function testGetFullClassName(): void
    {
        $filePath = __DIR__ . '/../src/Controller/RegisterController.php';
        $result = $this->router->getFullClassName($filePath);

        $this->assertSame('Crud\Controller\RegisterController', $result);
    }

    /**
     * @throws IncorrectRoutePathException
     */

    final public function testRegisterControllers(): void
    {
        $controllerDir = __DIR__ . '/../src/Controller';

        $this->router->registerControllers($controllerDir);
        $routes = $this->router->getRoutes();

        $this->assertNotEmpty($routes, 'No routes were registered. Ensure controllers have #[Path] attributes.');

        $expectedRoutes = [
            '/user/create',
            '/user/:id',
            '/user/:id/edit',
            '/user/:id/delete'
        ];

        foreach ($expectedRoutes as $route) {
            $this->assertArrayHasKey($route, $routes, "Route '$route' was not registered.");
        }
    }

    public static function provideTestGetControllerData(): array
    {
        return [
            ['/user/create', CreateUser::class],
            ['/user/:id', ViewUser::class],
            ['/user/:id/edit', UpdateUser::class],
            ['/user/:id/delete', DeleteUser::class],
        ];
    }

}

