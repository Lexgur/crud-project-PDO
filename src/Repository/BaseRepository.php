<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Connection;
class BaseRepository
{
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}