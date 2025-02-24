<?php

declare(strict_types=1);

namespace Crud;

use Crud\Controller\CreateStudent;
use Crud\Controller\CreateUser;
use Crud\Controller\DeleteStudent;
use Crud\Controller\UpdateStudent;
use Crud\Controller\ViewStudents;
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
        'create_user' => CreateUser::class
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

        $studentValidator = new StudentValidator();
        $userValidator = new UserValidator();

        // Repositories
        $studentRepository = new StudentModelRepository($connection);
        $userRepository = new UserModelRepository($connection);

        // Get action
        $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);
        $action = $request['action'] ?? null;

        $controllerClass = $this->actions[$action];

        // Switch naudojamas tam kad pakeistu controlleri priklausomai nuo actiono
        switch ($controllerClass) {
            case CreateStudent::class:
            case UpdateStudent::class:
            case DeleteStudent::class:
            case ViewStudents::class:
                $controller = new $controllerClass($studentValidator, $studentRepository, $template);
                break;
            case CreateUser::class:
                $controller = new $controllerClass($userValidator, $userRepository, $template);
                break;
            default:
                throw new Exception("Controller not found for action: " . htmlspecialchars($action));
        }

        print $controller();
    }
}