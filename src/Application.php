<?php

declare(strict_types=1);

namespace Crud;

use Crud\Controller\CreateStudent;
use Crud\Repository\StudentRepository;
use Crud\Validation\StudentValidator;
use Crud\Template;

class Application
{
    private Connection $connection;

    private array $actions = [

        'create_student' => CreateStudent::class
    ];

    public function __construct()
    {

    }

    public function run(): void
    {
        global $config;
        $configPath = __DIR__ . '/../config.php';
        include $configPath;


        //Dependencies
        $dbconfig = $config['db'];
        $dsn = "mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']}";

        $this->connection = new Connection($dsn, $dbconfig['username'], $dbconfig['password']);
        $connection = $this->connection->connect();

        $template = new Template($config['templates']);

        $studentValidator = new StudentValidator();

        $studentRepository = new StudentRepository($connection);

        //Controller
        $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);
        $controller = $this->actions[$request['action']];
        $controller = new $controller($studentValidator, $studentRepository, $template);
        $controller();
    }
}