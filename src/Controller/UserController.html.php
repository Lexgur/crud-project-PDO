<?php
global $connection;
include('src/Include/DatabaseConnection.php');
?>
<div class="box1">
    <h2>ALL STUDENTS</h2>
    <a href="SearchStudentController.php" class="btn btn-info">
        FIND STUDENT</a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        ADD STUDENTS
    </button>
</div>
<table class="table table-hover table-bordered table-str">
    <thead>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Age</th>
        <th>Update</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $query = 'select * from `students`';
    $result = $connection->query($query);
    if (!$result) {
        die("query Failed" . mysqli_error($connection));
    } else {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?php echo $row['id_student']; ?></td>
                <td><?php echo $row['student_first_name']; ?></td>
                <td><?php echo $row['student_last_name']; ?></td>
                <td><?php echo $row['student_age']; ?></td>
                <td><a href="UpdateStudentController.php?id=<?php echo $row['id_student']; ?>"
                       class="btn btn-success">
                        Update</a></td>
                <td><a href="DeleteStudentController.php?id=<?php echo $row['id_student']; ?>"
                       class="btn btn-danger">
                        Delete</a></td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
</table>

<?php
if (isset($_GET['message'])) {
    echo "<h6 class='why'>" . $_GET['message'] . "</h6>";
}
?>

<?php
if (isset($_GET['msg'])) {
    echo "<h6 class='yes'>" . $_GET['msg'] . "</h6>";
}
?>

<?php
if (isset($_GET['update_msg'])) {
    echo "<h6 class='yes'>" . $_GET['update_msg'] . "</h6>";
}
?>

<?php
if (isset($_GET['delete_msg'])) {
    echo "<h6 class='why'>" . $_GET['delete_msg'] . "</h6>";
}
?>