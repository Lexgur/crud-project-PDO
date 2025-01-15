<?php
namespace Crud\Command;

global $connection;

use PDO;

include('../Include/Header.php');
include('../Include/DatabaseConnection.php');

class SearchStudentController
{

}

// Initialize variables
$searchResults = [];
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lname = trim($_POST['l_name']);

    if (!empty($lname)) {
        // Search query
        $query = "SELECT * FROM `students` WHERE `student_last_name` LIKE :lname";
        $stmt = $connection->prepare($query);
        $searchTerm = '%' . $lname . '%'; // Allows partial matches
        $stmt->bindParam(':lname', $searchTerm, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error = "Query Failed: " . implode(", ", $stmt->errorInfo());
        }
    } else {
        $error = "Please enter a last name to search.";
    }


}
?>

<div class="container">
    <h2>Search Students by Last Name</h2>

    <!-- Display Errors -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <form action="SearchStudentController.php" method="post">
        <div class="form-group">
            <label for="l_name">Last Name:</label>
            <input type="text" name="l_name" class="form-control" placeholder="Enter last name">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Display Results -->
    <?php if (!empty($searchResults)): ?>
        <h3>Search Results</h3>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($searchResults as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_student']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_age']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p>No students found with the last name "<?php echo htmlspecialchars($lname); ?>".</p>
    <?php endif; ?>
</div>

<?php
include('../Include/Footer.php');
?>
