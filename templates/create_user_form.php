<html lang="en">
<head>
    <title>Register User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form method='post'>
    <div>
        <label for="user_email">Email:</label>
        <input type="text" id="user_email" name="email"><br><br>
    </div>
    <div>
        <label for="user_password">Password:</label>
        <input type="password" id="user_password" name="password" minlength="8" required><br><br>
    </div>
      <div>
        <button class="add-btn" type="submit" value="Create user">REGISTER</button>
    </div>
</form>
</body>
</html>