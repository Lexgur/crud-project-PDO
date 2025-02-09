<?php

declare(strict_types=1);

namespace Crud;

use Crud\Controller\CreateStudent;
use Crud\Controller\DeleteStudent;
use Crud\Controller\UpdateStudent;
use Crud\Controller\ViewStudents;
use Crud\Repository\StudentRepository;
use Crud\Validation\StudentValidator;

class Application
{
    private array $actions = [
        'create_student' => CreateStudent::class,
        'update_student' => UpdateStudent::class,
        'delete_student' => DeleteStudent::class,
        'view_students' => ViewStudents::class
    ];

    public function run(): void
    {
        global $config;
        $configPath = __DIR__ . '/../config.php';
        include $configPath;

        //Dependencies
        $dbconfig = $config['db'];
        $dsn = "mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']}";

        $database = new Connection($dsn, $dbconfig['username'], $dbconfig['password']);
        $connection = $database->connect();

        $template = new Template($config['templates']);

        $studentValidator = new StudentValidator();

        $studentRepository = new StudentRepository($connection);

        //Controller
        $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);
        $controller = $this->actions[$request['action']];
        $controller = new $controller($studentValidator, $studentRepository, $template);

        print $controller();

    }
}