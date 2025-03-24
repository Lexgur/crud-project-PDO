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
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard-container">
    <h1>Sveiki, <?php echo htmlspecialchars($userEmail); ?>!</h1>
    <p class="welcome-text">Sveiki atvykę į valdymo skydelį.</p>

    <!-- Navigation Buttons -->
    <div class="dashboard-actions">
        <button class="add-btn">
            <a href="/students" target="_blank" rel="noopener">Peržiūrėti studentus</a>
        </button>
        <button class="upd-btn">
            <a href="/user/:id/edit" target="_blank" rel="noopener">Atnaujinti profilį</a>
        </button>
        <button class="dlt-btn">
            <a href="/logout">Atsijungti</a>
        </button>
    </div>

    <!-- Fancy Decorative Element -->
    <div class="dashboard-footer">
        <p>Valdymo skydelis © <?php echo date('Y'); ?></p>
    </div>
</div>
</body>
</html>