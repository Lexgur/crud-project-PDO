<?php
namespace Crud\Controller;

global $connection;

use PDO;


include('../Include/Header.php');
include('../Include/DatabaseConnection.php');

class UpdateStudentController
{

}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch student data by ID
    $query = "SELECT * FROM `students` WHERE `id_student` = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            header("Location:../../index.html.php?error=Student not found.");
            exit;
        }
    } else {
        die("Query Failed: " . implode(", ", $stmt->errorInfo()));
    }
} else {
    // If no ID is provided, redirect with an error
    header("Location:../../index.html.php?error=Student ID not found.");
    exit;
}

if (isset($_POST["update_students"])) {
    $fname = trim($_POST['f_name']);
    $lname = trim($_POST['l_name']);
    $age = intval($_POST['age']); // Ensure `$age` is an integer

    if (empty($fname) || empty($lname) || $age <= 0) {
        header("Location:../../UpdateStudentController.php?id=$id&error=Invalid input data.");
        exit;
    }

    // Update the student data
    $query = "UPDATE `students` 
              SET `student_first_name` = :fname,
                  `student_last_name` = :lname,
                  `student_age` = :age 
              WHERE `id_student` = :id";

    $stmt = $connection->prepare($query);
    $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location:../../index.html.php?update_msg=Student updated.');
        exit;
    } else {
        die("Query Failed: " . implode(", ", $stmt->errorInfo()));
    }
}
?>

<?php
include("../Form/StudentUpdate.html.php");
?>


