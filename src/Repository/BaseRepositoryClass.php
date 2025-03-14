<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Connection;

class BaseRepositoryClass
{
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}