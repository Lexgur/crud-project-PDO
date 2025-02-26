<?php
session_start();

if (!isset($_SESSION['userEmail'])) {
    header("Location: create_user_form.php");
    exit();
}

$userEmail = $_SESSION['userEmail'];
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        a {
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sveiki, <?php echo htmlspecialchars($userEmail); ?>!</h1>
    <p>Sveiki atvykę į valdymo skydelį.</p>
    <a href='logout_user.php'>Atsijungti</a>
</div>

</body>
</html>
