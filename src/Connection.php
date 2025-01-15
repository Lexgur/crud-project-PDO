<?php declare(strict_types=1); namespace Crud;


use PDO;
use PDOException;
    class Connection {

    }
$username = "root";
$password = "root123";

for ($i = 0; $i < 3; $i++) {
    try {
        $connection = new PDO('mysql:host=localhost;dbname=crud_operation', $username, $password);
    } catch (PDOException $e) {
        echo "You shall !not pass!";
        usleep(500);
    }
}