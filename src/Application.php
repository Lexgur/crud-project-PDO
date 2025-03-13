<?php

declare(strict_types=1);

namespace Crud;

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
use Exception;
use Crud\DependencyInjection\Container;

class Application
{
    private array $actions = [
        'create_student' => CreateStudent::class,
        'update_student' => UpdateStudent::class,
        'delete_student' => DeleteStudent::class,
        'view_students' => ViewStudents::class,
        'create_user' => CreateUser::class,
        'update_user' => UpdateUser::class,
        'delete_user' => DeleteUser::class,
        'view_user' => ViewUser::class,
        'register_user' => RegisterController::class,
        'login_user' => LoginController::class,
        'logout_user' => LogoutController::class,
    ];

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

        $database = new Connection($dsn, $dbconfig['username'], $dbconfig['password']);
        $database->connect();

        $container = new Container($parameters);

        $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);
        $action = $request['action'] ?? null;

        $controllerClass = $this->actions[$action] ?? null;
        if ($controllerClass === null) {
            throw new Exception("Controller not found for action: " . htmlspecialchars($action));
        }

        $controller = $container->get($controllerClass);

        print $controller();
    }
}
