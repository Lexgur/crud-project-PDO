<?php

declare(strict_types=1);

use Crud\Controller\CreateUser;
use Crud\Controller\DeleteStudent;
use Crud\Controller\DeleteUser;
use Crud\Controller\UpdateStudent;
use Crud\Controller\UpdateUser;
use Crud\Controller\ViewUser;
use Crud\Exception\IncorrectRoutePathException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Crud\Core\Router;

class RouterTest extends TestCase
{
    private Router $router;

    /**
     * @throws IncorrectRoutePathException
     */
    protected function setUp(): void
    {
        $this->router = new Router();
        $this->router->registerControllers();
    }

    #[DataProvider('provideTestGetControllerData')]
    final public function testGetController(string $routePath, string $expectedController): void
    {
        $controller = $this->router->getController($routePath);
        $this->assertSame($expectedController, $controller);
    }

    #[DataProvider('provideTestGetControllerThrowsIncorrectRoutePathException')]
    final public function testGetControllerThrowsIncorrectRoutePathException(string $routePath, string $expectedController): void
    {
        $this->expectException(IncorrectRoutePathException::class);

        $controller = $this->router->getController($routePath);
        $this->assertSame($expectedController, $controller);
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

    final public function testRegisterControllers(): void
    {
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
            ['/user/17', ViewUser::class],
            ['/user/36/edit', UpdateUser::class],
            ['/user/27/delete', DeleteUser::class],
            ['/user/61/edit', UpdateUser::class],
            ['/user/22/edit', UpdateUser::class],
            ['/student/61/edit', UpdateStudent::class],
            ['/student/22/edit', UpdateStudent::class],
            ['/student/99/delete', DeleteStudent::class],
        ];
    }

    public static function provideTestGetControllerThrowsIncorrectRoutePathException(): array
    {
        return [
            ['/student/senas/delete', DeleteStudent::class],
            ['/student/#7758/delete', DeleteStudent::class],
            ['/student/^21^/delete', DeleteStudent::class],
            ['/student//delete', DeleteStudent::class],
            ['/student/  /delete', DeleteStudent::class],
            ['/student/112*/delete', DeleteStudent::class],
        ];
    }

}
