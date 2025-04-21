<?php
session_start();

if (!isset($_SESSION['id']) || trim($_SESSION['id']) == '') {
    header('location:index.php');
    exit();
}

include('conn.php');
$query = mysqli_query($conn, "SELECT * FROM user WHERE userid='".$_SESSION['id']."'");
$row = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Success</title>
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
        .success-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        h2 {
            color: #28a745;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h2>Login Success</h2>
        <p>Welcome, <?php echo htmlspecialchars($row['fullname']); ?>!</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>