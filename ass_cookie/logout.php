<?php
session_start();
session_destroy();

if (isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
    setcookie("user", '', time() - 3600, "/");
    setcookie("pass", '', time() - 3600, "/");
}

header('location:index.php');
exit();
?>