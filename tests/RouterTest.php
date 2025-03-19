<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\Core\Router;

class RouterTest extends TestCase
{
    #[DataProvider('provideTestValidRouteReturnsExpectedControllerData')]
    final public function testValidRouteReturnsExpectedController(string $routePath, string $expectedController): void
    {
        $router = new Router();
        $controller = $router->getController($routePath);

        $this->assertSame($expectedController, $controller);
    }

    public static function provideTestValidRouteReturnsExpectedControllerData(): array
    {
        return [
            '/users' => ViewUsersController::class,
            '/user/create' => CreateUserController::class,
            '/user/:id' => ViewUserController::class,
            '/user/:id/edit' => UpdateUserController::class,
            '/user/:id/delete' => DeleteUserController::class
        ];
    }
}
class ViewUsersController
{

}
class CreateUserController
{

}

class ViewUserController
{

}

class UpdateUserController
{

}

class DeleteUserController
{

}
