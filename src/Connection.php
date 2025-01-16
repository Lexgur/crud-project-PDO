<?php

declare(strict_types=1);

namespace Crud;

use PDO;

class Connection
{

    private string $dsn;
    private string $username;
    private string $password;

    public function __construct(string $dsn, string $username, string $password)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect(): PDO
    {
        return new PDO($this->dsn, $this->username, $this->password);
    }
}


