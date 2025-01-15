<form action="//update_page_1.php?id=<?php echo $id; ?>" method="post">
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

