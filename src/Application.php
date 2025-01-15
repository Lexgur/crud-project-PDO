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
        $this->connection = new Connection();
    }
    public function run(): void
    {
        //Dependencies

        $connection = $this->connection->connect();
        $template = new Template();

       $request = filter_var_array($_GET, ['action' => FILTER_SANITIZE_ENCODED]);

       $controller = $this->actions[$request['action']];

       $controller = new $controller($connection, $template);
       $controller();
       var_dump($controller);
    }
}


