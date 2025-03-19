<?php

declare(strict_types=1);

use Crud\Exception\IncorrectRoutePathException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\Core\Router;

class RouterTest extends TestCase
{
    #[DataProvider('provideTestGetControllerData')]
    final public function testGetController(string $routePath, string $expectedController): void
    {
        $router = new Router();
        $controller = $router->getController($routePath);

        $this->assertInstanceOf($expectedController, $controller);
    }
    final public function testIncorrectPathThrowsIncorrectRoutePathException(): void
    {
        $router = new Router();

        $this->expectException(IncorrectRoutePathException::class);

        $router->getController('/incorrect');
    }

    public static function provideTestGetControllerData(): array
    {
        return [
            ['/users' => ViewUsersController::class],
            ['/user/create' => CreateUserController::class],
            ['/user/:id' => ViewUserController::class],
            ['/user/:id/edit' => UpdateUserController::class],
            ['/user/:id/delete' => DeleteUserController::class]
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
