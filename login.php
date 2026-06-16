<?php
require_once 'database.php';
require_once 'user.php';

$user = new User();
$user->connect();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $naam = $_POST['naam'];
    $password = $_POST['password'];

    if ($user->login($naam, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Onjuist naam of wachtwoord.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
        <header class="site-header">
            <div class="site-title">
                Student Management System
            </div>
        </header>

        <div class="page-center-auth">

            <div class="auth-container">

                <h1>Welcome!</h1>

                <?php if (!empty($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>

                <form method="post">

                    <input
                        type="text"
                        name="naam"
                        placeholder="Username"
                        required
                    >

                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    >

                    <button
                        class="login-btn"
                        type="submit">
                        Log In
                    </button>
                </form>

                <button
                    class="signup-btn"
                    onclick="window.location='signup.php'">
                    Create Account
                </button>

                <button
                    class="back-btn"
                    onclick="window.location='index.php'">
                    Continue as Guest
                </button>
            </div>
        </div>


        <footer class="site-footer">
            Student Management System © 2026
        </footer>
</body>
</html>