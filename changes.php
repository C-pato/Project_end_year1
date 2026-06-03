
<?php
require_once 'backend.php';
require_once 'user.php';
$user = new user();
$user->connect();




if (isset($_GET['name']) && isset($_GET['email']) && isset($_GET['password'])) {
    echo $user->delete($_GET['name'], $_GET['email'], $_GET['password']);
}
?> 


<form method="get">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Submit">
</form>