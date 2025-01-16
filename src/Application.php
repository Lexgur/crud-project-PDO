<?php

declare(strict_types=1);

namespace Crud;

use Crud\Controller\CreateStudent;

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
        $template = new Template();
        $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);

        $controller = $this->actions[$request['action']];

        $controller = new $controller($connection, $template);
        $controller();
        var_dump($controller);
    }
}


