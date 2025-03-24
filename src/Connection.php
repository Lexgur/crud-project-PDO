<?php

declare(strict_types=1);

namespace Crud;

use PDO;
use PDOException;

class Connection
{
    private string $dsn;
    private string $username;
    private string $password;
    private ?PDO $pdo = null;

    public function __construct(string $dsn, string $username = '', string $password = '')
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect(): PDO
    {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO($this->dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

}
