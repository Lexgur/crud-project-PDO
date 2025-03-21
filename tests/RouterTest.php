<?php

declare(strict_types=1);

use Crud\Attribute\Path;
use Crud\Exception\IncorrectRoutePathException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\Core\Router;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        $this->router = new Router();
    }
    #[DataProvider('provideTestGetControllerData')]
    final public function testGetController(string $routePath, string $expectedController): void
    {
        $controllerDir = __DIR__ . '/../src/Controller';
        $this->router->registerControllers($controllerDir);

        $controller = $this->router->getController($routePath);

        $this->assertInstanceOf($expectedController, $controller[0]);
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
            ['/users', ViewUsersController::class],
            ['/user/create', CreateUserController::class],
            ['/user/:id', ViewUserController::class],
            ['/user/:id/edit', UpdateUserController::class],
            ['/user/:id/delete', DeleteUserController::class]
        ];
    }
}
#[Path('/users')]
class ViewUsersController
{
}
#[Path('/user/create')]
class CreateUserController
{
}
#[Path('/user/:id')]
class ViewUserController
{
}
#[Path('/user/:id/edit')]
class UpdateUserController
{
}
#[Path('/user/:id/delete')]
class DeleteUserController
{
}

