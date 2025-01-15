<?php

declare(strict_types=1);

namespace Crud;

use PDO;
use PDOException;

class Connection
{
    public function connect(): \PDO
    {
       return new PDO("mysql:host=localhost;dbname=crud_operation", "root", "root123");
    }
}

