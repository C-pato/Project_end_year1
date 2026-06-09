<?php
require_once 'backend.php';
require_once 'user.php';

$user = new User();
$user->connect();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Temporary login check
    if ($username == "admin" && $password == "1234") {

        header("Location: frontend.php");
        exit();

    } else {

        $error = "Invalid username or password.";

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
<body>
        <header class="site-header">
            <div class="site-title">
                Student Management System
            </div>
        </header>

        <div class="page-center">

            <div class="auth-container">

                <h1>Welcome!</h1>

                <?php if (!empty($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                
                <form method="post">

                    <input
                        type="text"
                        name="username"
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
            </div>
        </div>

        <footer class="site-footer">
            Student Management System © 2026
        </footer>



    <div>
        <div id="home"><a  href="index.php?pagina=404" style="color: white;" >  </a> </div> 
        <div id="home"><a  href="index.php?pagina=frontend" style="color: white;" >  </a> </div> 
        <div id="home"><a  href="index.php?pagina=pages" style="color: white;" > Uitloggen </a> </div>
        <div id="home"><a  href="index.php?pagina=user" style="color: white;" > Uitloggen </a> </div>  
        <div id="home"><a  href="index.php?pagina=backend" style="color: white;" > Uitloggen </a> </div>  
    </div>
</body>
</html>