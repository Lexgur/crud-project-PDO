<html lang="en">
<head>
    <title>Login User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form method="post">
    <div>
        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="email" placeholder="Enter your email" required><br><br>
    </div>
    <div>
        <label for="user_password">Password:</label>
        <input type="password" id="user_password" name="password" minlength="8" placeholder="Enter your password" required><br><br>
    </div>
    <div>
        <button class="add-btn" type="submit">Login</button>
    </div>
</form>
</body>
</html>
