<?php

declare(strict_types=1);

namespace Crud\Core;

use Crud\Controller\CreateStudent;
use Crud\Controller\CreateUser;
use Crud\Controller\DeleteStudent;
use Crud\Controller\DeleteUser;
use Crud\Controller\LoginController;
use Crud\Controller\LogoutController;
use Crud\Controller\RegisterController;
use Crud\Controller\UpdateStudent;
use Crud\Controller\UpdateUser;
use Crud\Controller\ViewStudents;
use Crud\Controller\ViewUser;
use Crud\Exception\IncorrectRoutePathException;

class Router
{
    private array $routes = [
        '/user/create' => CreateUser::class,
        '/user/:id' => ViewUser::class,
        '/user/:id/edit' => UpdateUser::class,
        '/user/:id/delete' => DeleteUser::class,
        '/students' => ViewStudents::class,
        '/student/create' => CreateStudent::class,
        '/student/:id/edit' => UpdateStudent::class,
        '/student/:id/delete' => DeleteStudent::class,
        '/register' => RegisterController::class,
        '/login' => LoginController::class,
        '/logout' => LogoutController::class
    ];

    public function getController(string $routePath): object
    {
        if (!array_key_exists($routePath, $this->routes)) {
            throw new IncorrectRoutePathException("Route path '$routePath' not found.");
        }
        $controllerClass = $this->routes[$routePath];
        return new $controllerClass();
    }
}