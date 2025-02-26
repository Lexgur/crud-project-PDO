<?php

declare(strict_types=1);

namespace Crud;

use Crud\Controller\CreateStudent;
use Crud\Controller\CreateUser;
use Crud\Controller\DeleteStudent;
use Crud\Controller\DeleteUser;
use Crud\Controller\LoginController;
use Crud\Controller\RegisterController;
use Crud\Controller\UpdateStudent;
use Crud\Controller\UpdateUser;
use Crud\Controller\ViewStudents;
use Crud\Controller\ViewUser;
use Crud\Repository\StudentModelRepository;
use Crud\Repository\UserModelRepository;
use Crud\Validation\StudentValidator;
use Crud\Validation\UserValidator;
use Exception;

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
    ];

    public function run(): void
    {
        global $config;
        $configPath = __DIR__ . '/../config.php';
        include $configPath;

        // Database configuration
        $dbconfig = $config['db'];
        $dsn = "mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']}";
        $database = new Connection($dsn, $dbconfig['username'], $dbconfig['password']);
        $connection = $database->connect();

        $template = new Template($config['templates']);

        // Validators
        $studentValidator = new StudentValidator();
        $userValidator = new UserValidator($connection);

        // Repositories
        $studentRepository = new StudentModelRepository($connection);
        $userRepository = new UserModelRepository($connection);

        // Get action
        $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);
        $action = $request['action'] ?? null;

        $controllerClass = $this->actions[$action];

        // Switch naudojamas tam kad pakeistu controlleri priklausomai nuo actiono
        $controller = match ($controllerClass) {
            CreateStudent::class, UpdateStudent::class, DeleteStudent::class, ViewStudents::class => new $controllerClass($studentValidator, $studentRepository, $template),
            CreateUser::class, UpdateUser::class, DeleteUser::class, ViewUser::class, RegisterController::class, LoginController::class => new $controllerClass($userValidator, $userRepository, $template),
            default => throw new Exception("Controller not found for action: " . htmlspecialchars($action)),
        };

        print $controller();
    }
}