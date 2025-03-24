<?php

declare(strict_types=1);

namespace Crud;

use Crud\Core\Router;
use Exception;
use Crud\DependencyInjection\Container;

class Application
{
    /**
     * @throws \ReflectionException
     * @throws Exception
     */
    public function run(): void
    {
        global $config;
        $configPath = __DIR__ . '/../config.php';
        $config = include $configPath;

        //Database
        $dbconfig = $config['db'];
        $dsn = "mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']}";
        $parameters = [
            'dsn' => $dsn,
            'username' => $dbconfig['username'],
            'password' => $dbconfig['password'],
            'templatePath' => $config['templates'],
        ];

        $container = new Container($parameters);

        $router = new Router();
        $router->registerControllers();

        $requestUri = $_SERVER['REQUEST_URI'];
        $baseUri = 'http://localhost:8000';
        $routePath = str_replace($baseUri, '', $requestUri);

        $controllerClass = $router->getController($routePath);

        $controller = $container->get($controllerClass);

        print $controller();
    }
}
