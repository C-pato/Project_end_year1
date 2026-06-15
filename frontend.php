<?php
require_once 'database.php';

$db = new Database();
$conn = $db->connect();

$sql = "SELECT id, voornaam, achternaam, klas FROM studenten";
$result = mysqli_query($conn, $sql);

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $id = intval($_POST['id']);
    $voornaam = mysqli_real_escape_string($conn, $_POST['voornaam']);
    $achternaam = mysqli_real_escape_string($conn, $_POST['achternaam']);
    $klas = mysqli_real_escape_string($conn, $_POST['klas']);
    
    $check_sql = "SELECT id FROM studenten WHERE id = $id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "ID already exists!";
    } else {
        $insert_sql = "INSERT INTO studenten (id, voornaam, achternaam, klas) VALUES ($id, '$voornaam', '$achternaam', '$klas')";
        if (mysqli_query($conn, $insert_sql)) {
            $success = "Student added successfully!";
            // Refresh the result
            $result = mysqli_query($conn, $sql);
        } else {
            $error = "Error adding student: " . mysqli_error($conn);
        }
    }
}
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

                <div class="student-header">
                    <h1>Student list</h1>  
                    <button
                        class="logout-btn"
                        onclick="window.location='logout.php'">
                        Logout
                    </button>
                </div>

                <table class="student-table">
                    <tr>
                        <th>ID</th>
                        <th>Voornaam</th>
                        <th>Achternaam</th>
                        <th>Klas</th>
                        <th>Bewerken</th>
                    </tr>

                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['voornaam'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['achternaam'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['klas'] ?? ''); ?></td>
                            <td>
                                <a class='action-btn' href="student.php?id=<?php echo $row['id']; ?>">Bewerk</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <div class="add-student-section">
                    <button class="add-student-btn" onclick="toggleAddForm()">+ Add Student</button>
                    
                    <form id="add-form" method="post" style="display: none; margin-top: 20px; padding: 20px; border: 2px dashed rgba(255,255,255,0.5); border-radius: 10px;">
                        <?php if (!empty($error)): ?>
                            <p class="error"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($success)): ?>
                            <p class="success"><?php echo htmlspecialchars($success); ?></p>
                        <?php endif; ?>
                        
                        <input type="text" name="id" placeholder="Student ID" required>
                        <input type="text" name="voornaam" placeholder="First Name" required>
                        <input type="text" name="achternaam" placeholder="Last Name" required>
                        <input type="text" name="klas" placeholder="Klas" required>
                        
                        <button type="submit" name="add_student" class="login-btn" style="margin-top: 10px;">Add Student</button>
                        <button type="button" class="back-btn" onclick="toggleAddForm()" style="margin-top: 10px;">Cancel</button>
                    </form>
                </div>
            </div>

        </div>

        <script>
            function toggleAddForm() {
                var form = document.getElementById('add-form');
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            }
        </script>


        <footer class="site-footer">
            Student Management System © 2026
        </footer>
</body>
</html>
