<?php

declare(strict_types=1);

use Crud\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    function testConnect(): void
    {
        $dsn = 'mysql:host=localhost;dbname=crud_operation_test';
        $username = 'root';
        $password = 'root123';
        $connection = new Connection($dsn, $username, $password);
        $pdo = $connection->connect();

        $this->assertInstanceOf(PDO::class, $pdo);
    }

    function testReturnError(): void
    {
        $dsn = 'mysql:host=localhost;dbname=crud_operation_test';
        $username = 'root';
        $password = 'root222';
        $connection = new Connection($dsn, $username, $password);

        $this->expectException(PDOException::class);

        $connection->connect();
    }
}
