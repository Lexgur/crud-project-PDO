<?php namespace Crud\Controller;
global $connection;
use PDO;


include('../Controller/dbcon.php');

class delete_page {

}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $query = "DELETE FROM `students` WHERE id_student = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header('location:../../index.php?delete_msg=You have deleted the record.');
        } else {
            die("Query Failed: " . implode(", ", $stmt->errorInfo()));
        }
    } catch (\PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header('location:../../index.php?delete_msg=Invalid Request.');
}

