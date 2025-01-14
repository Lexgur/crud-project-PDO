<?php  namespace Crud;

global $connection;

use PDO;
include('src/Includes/header.php');
include('src/Controllers/dbcon.php');
include('vendor/autoload.php');
?>

    <div class="box1">
        <h2>ALL STUDENTS</h2>
        <a href="src/Controllers/search_students.php" class="btn btn-info">
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
                    <td><a href="src/Controllers/update_page_1.php?id=<?php echo $row['id_student']; ?>" class="btn btn-success">
                            Update</a></td>
                    <td><a href="src/Controllers/delete_page.php?id=<?php echo $row['id_student']; ?>" class="btn btn-danger">
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

    <!-- Modal -->
    <form action="src/Controllers/insert_data.php" method="post">
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ADD STUDENT</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="f_name">First Name :
                                <input type="text" name="f_name" class="form-control">
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="l_name">Last Name :
                                <input type="text" name="l_name" class="form-control">
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="age">Age :
                                <input type="number" name="age" class="form-control">
                            </label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary btn-success" name="add_students" value="ADD">
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php include('src/Includes/footer.php'); ?>