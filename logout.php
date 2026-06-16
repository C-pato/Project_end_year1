<?php
session_start();
require_once 'user.php';

$user = new user();

if (isset($_POST['logout'])) {
    session_destroy();
    $_SESSION = [];
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

        <header class="site-header">
            <div class="site-title">
                Student Management System
            </div>
        </header>

        <div class="page-center-auth">
            <div class="auth-container">
                <h1>Goodbye!</h1>
                <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px;">You have been logged out successfully.</p>
                
                <form method="post">
                    <button name="logout" type="submit" class="login-btn">
                        Back to Login
                    </button>
                </form>
            </div>
        </div>
    


        <footer class="site-footer">
            Student Management System © 2026
        </footer>
</body>
</html>