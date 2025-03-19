<?php

declare(strict_types=1);

use Crud\Connection;
use Crud\DependencyInjection\Container;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnect(): void
    {
        $dsn = 'mysql:host=localhost;dbname=crud_operation_test';
        $username = 'root';
        $password = 'root123';
        $parameters = [
            'dsn' => $dsn,
            'username' => $username,
            'password' => $password,
        ];
        $container = new Container($parameters);
        $connection = $container->get(Connection::class);

        $pdo = $connection->connect();

        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function testReturnError(): void
    {
        $dsn = 'mysql:host=localhost;dbname=crud_operation_test';
        $username = 'root';
        $password = 'root222';
        $parameters = [
            'dsn' => $dsn,
            'username' => $username,
            'password' => $password,
        ];
        $container = new Container($parameters);
        $connection = $container->get(Connection::class);

        $this->expectException(PDOException::class);

        $connection->connect();
    }
}
