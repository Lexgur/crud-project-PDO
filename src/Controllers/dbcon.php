<?php namespace Crud\Controllers;
use PDO;
use PDOException;

$username = 'root';
$password = 'root123';

try {

    $connection = new PDO('mysql:host=127.0.0.1;port=3306;dbname=crud_operation', $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch (PDOException $e) {

    die("Connection failed: " . $e->getMessage());
}
?>
