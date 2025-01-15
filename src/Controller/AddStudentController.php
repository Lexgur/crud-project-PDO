<?php
namespace Crud\Command;

global $connection;

use PDO;
use PDOException;

include('../Include/header.php');
include('../Include/DatabaseConnection.php');

class AddStudentController
{

}

if (isset($_POST['add_students'])) {

    $fname = trim($_POST['f_name']); // trim
    $lname = trim($_POST['l_name']);
    $age = intval($_POST['age']);

    // Validate inputs
    if (empty($fname)) {
        header('location:../../index.html.php?message=Oh come on... Really?');
        exit;
    }

    try {
        // PDO simta kart geriau
        $query = "INSERT INTO `students` (student_first_name, student_last_name, student_age)
                  VALUES (:fname, :lname, :age)";
        $stmt = $connection->prepare($query);


        $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);


        if ($stmt->execute()) {
            header('location:../../index.html.php?msg=Student has been added');
        } else {
            $errorInfo = $stmt->errorInfo();
            die("Query Failed: " . $errorInfo[2]);
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}
?>
