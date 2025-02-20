<?php

declare(strict_types=1);

namespace Crud\Repository;

use PDO;

class BaseRepositoryClass
{
    public function __construct(
        protected PDO $connection
    ) {

    }
}