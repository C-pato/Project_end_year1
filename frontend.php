<?php
require_once 'backend.php';

$db = new Database();
$conn = $db->connect();

$sql = "SELECT id, naam, email, role FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
            <header class="site-header">
            <div class="site-title">
                Student Management System
            </div>
        </header>

        <div class="page-center">

            <div class="board-container">

                <h1>Student list</h1>  

                <table class="student-table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>

                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['naam']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>

        </div>


        <footer class="site-footer">
            Student Management System © 2026
        </footer>
</body>
</html>