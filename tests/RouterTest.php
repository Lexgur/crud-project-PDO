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

        $this->assertArrayHasKey('/user/create', $routes);
        $this->assertArrayHasKey('/user/:id', $routes);
        $this->assertArrayHasKey('/user/:id/edit', $routes);
        $this->assertArrayHasKey('/user/:id/delete', $routes);
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
    public function __invoke()
    {
        return 'View Users';
    }
}
#[Path('/user/create')]
class CreateUserController
{
    public function __invoke()
    {
        return 'Create User';
    }
}
#[Path('/user/:id')]
class ViewUserController
{
    public function __invoke()
    {
        return 'View User';
    }
}
#[Path('/user/:id/edit')]
class UpdateUserController
{
    public function __invoke()
    {
        return 'Update User';
    }
}
#[Path('/user/:id/delete')]
class DeleteUserController
{
    public function __invoke()
    {
        return 'Delete User';
    }
}

