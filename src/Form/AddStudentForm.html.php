<!-- Modal -->
<form action="../Controller/AddStudentController.php" method="post">
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
