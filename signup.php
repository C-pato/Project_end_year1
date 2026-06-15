<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign up</title>
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

                <h1>Sign Up!</h1>

                <p id="error" class="error" style="display:none;"></p>

                <form>

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

                    <input
                        type="password"
                        name="confirmPassword"
                        placeholder="Confirm Password"
                        required
                    >

                    <button
                        class="login-btn"
                        type="submit">
                        Sign Up
                    </button>

                </form>

                <button
                    class="back-btn"
                    onclick="window.location='index.php'">
                    Back to Login
                </button>
            </div>
        </div>

        <footer class="site-footer">
            Student Management System © 2026
        </footer>
</body>
</html>