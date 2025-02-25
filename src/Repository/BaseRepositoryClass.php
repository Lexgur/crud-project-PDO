<?php

declare(strict_types=1);

namespace Crud\Repository;

use PDO;

class BaseRepositoryClass
{
    protected PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }
}