<?php
session_start();
include('conn.php');

if (isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
    $username = $_COOKIE["user"];
    $password = $_COOKIE["pass"];
    $query = mysqli_query($conn, "SELECT * FROM `user` WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_array($query);
        $_SESSION['id'] = $row['userid'];
        header('location:success.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Using Cookie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="checkbox"] {
            margin-right: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            color: #dc3545;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Form</h2>
        <form method="POST" action="login.php">
            <label>Username:</label>
            <input type="text" value="<?php if (isset($_COOKIE["user"])) { echo $_COOKIE["user"]; } ?>" name="username" required>
            <label>Password:</label>
            <input type="password" value="<?php if (isset($_COOKIE["pass"])) { echo $_COOKIE["pass"]; } ?>" name="password" required>
            <input type="checkbox" name="remember" <?php if (isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) { echo "checked"; } ?>> Remember me
            <input type="submit" value="Login" name="login">
        </form>
        <div class="message">
            <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
        </div>
    </div>
</body>
</html>