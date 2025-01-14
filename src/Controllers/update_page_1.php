<?php
namespace Crud\Controllers;

global$connection;
use PDO;

include('../Includes/header.php');
include('../Controllers/dbcon.php');

class update_page_1 {
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
            header("Location:../../index.php?error=Student not found.");
            exit;
        }
    } else {
        die("Query Failed: " . implode(", ", $stmt->errorInfo()));
    }
} else {
    // If no ID is provided, redirect with an error
    header("Location:../../index.php?error=Student ID not found.");
    exit;
}

if (isset($_POST["update_students"])) {
    $fname = trim($_POST['f_name']);
    $lname = trim($_POST['l_name']);
    $age = intval($_POST['age']); // Ensure `$age` is an integer

    if (empty($fname) || empty($lname) || $age <= 0) {
        header("Location:../../update_page_1.php?id=$id&error=Invalid input data.");
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
        header('Location:../../index.php?update_msg=Student updated.');
        exit;
    } else {
        die("Query Failed: " . implode(", ", $stmt->errorInfo()));
    }
}
?>

<form action="update_page_1.php?id=<?php echo $id; ?>" method="post">
    <div class="form-group">
        <label for="f_name">First Name:</label>
        <input type="text" name="f_name" class="form-control"
               value="<?php echo htmlspecialchars($row['student_first_name']); ?>">
    </div>
    <div class="form-group">
        <label for="l_name">Last Name:</label>
        <input type="text" name="l_name" class="form-control"
               value="<?php echo htmlspecialchars($row['student_last_name']); ?>">
    </div>
    <div class="form-group">
        <label for="age">Age:</label>
        <input type="number" name="age" class="form-control"
               value="<?php echo htmlspecialchars($row['student_age']); ?>">
    </div>
    <input type="submit" class="btn btn-primary btn-success" name="update_students" value="UPDATE">
</form>

