<?php
session_unset();
session_destroy();

header("Location: create_user_form.php");
exit();
?>