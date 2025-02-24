<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional CSS file -->
</head>
<body>
<h2>User Details</h2>

<?php if (isset($user)): ?>
    <table>
        <tr>
            <th>User ID:</th>
            <td><?= htmlspecialchars($user->getUserId()) ?></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><?= htmlspecialchars($user->getUserEmail()) ?></td>
        </tr>
    </table>

    <!-- Update Form -->
    <form action="/index.php?action=update_user&id=<?= $user->getUserId() ?>" method="post">
        <label for="email">New Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getUserEmail()) ?>" required>
        <br>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit" class="upd-btn">Update</button>
    </form>

    <!-- Delete Button -->
    <form action="/index.php?action=delete_user&id=<?= $user->getUserId() ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
        <button type="submit" class="dlt-btn">Delete</button>
    </form>

<?php else: ?>
    <p>User not found.</p>
<?php endif; ?>

</body>
</html>
