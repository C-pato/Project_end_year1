<?php
require_once 'database.php';

$db = new Database();
$conn = $db->connect();

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";
$success = "";

if ($student_id == 0) {
    header("Location: frontend.php");
    exit;
}

// Fetch student data
$sql = "SELECT id, voornaam, achternaam, klas FROM studenten WHERE id = $student_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: frontend.php");
    exit;
}

$student = mysqli_fetch_assoc($result);

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $new_id = intval($_POST['id']);
    $voornaam = mysqli_real_escape_string($conn, $_POST['voornaam']);
    $achternaam = mysqli_real_escape_string($conn, $_POST['achternaam']);
    $klas = mysqli_real_escape_string($conn, $_POST['klas']);
    
    // Check if new ID already exists and is different from current ID
    if ($new_id != $student_id) {
        $check_sql = "SELECT id FROM studenten WHERE id = $new_id";
        $check_result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($check_result) > 0) {
            $error = "ID already exists!";
        } else {
            $update_sql = "UPDATE studenten SET id = $new_id, voornaam = '$voornaam', achternaam = '$achternaam', klas = '$klas' WHERE id = $student_id";
            
            if (mysqli_query($conn, $update_sql)) {
                $success = "Student updated successfully!";
                $student_id = $new_id;
                $student['id'] = $new_id;
                $student['voornaam'] = $voornaam;
                $student['achternaam'] = $achternaam;
                $student['klas'] = $klas;
            } else {
                $error = "Error updating student: " . mysqli_error($conn);
            }
        }
    } else {
        $update_sql = "UPDATE studenten SET voornaam = '$voornaam', achternaam = '$achternaam', klas = '$klas' WHERE id = $student_id";
        
        if (mysqli_query($conn, $update_sql)) {
            $success = "Student updated successfully!";
            $student['voornaam'] = $voornaam;
            $student['achternaam'] = $achternaam;
            $student['klas'] = $klas;
        } else {
            $error = "Error updating student: " . mysqli_error($conn);
        }
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $delete_sql = "DELETE FROM studenten WHERE id = $student_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: frontend.php");
        exit;
    } else {
        $error = "Error deleting student: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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
            <h1>Edit Student</h1>

            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <p class="success" style="color: #4ade80; background: rgba(74, 222, 128, 0.15); border: 1px solid rgba(74, 222, 128, 0.5); padding: 12px; margin-bottom: 20px; border-radius: 8px;"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>

            <form method="post">
                <input
                    type="text"
                    name="id"
                    placeholder="Student ID"
                    value="<?php echo htmlspecialchars($student['id'] ?? ''); ?>"
                    required
                >

                <input
                    type="text"
                    name="voornaam"
                    placeholder="First Name"
                    value="<?php echo htmlspecialchars($student['voornaam'] ?? ''); ?>"
                    required
                >

                <input
                    type="text"
                    name="achternaam"
                    placeholder="Last Name"
                    value="<?php echo htmlspecialchars($student['achternaam'] ?? ''); ?>"
                    required
                >

                <input
                    type="text"
                    name="klas"
                    placeholder="Klas"
                    value="<?php echo htmlspecialchars($student['klas'] ?? ''); ?>"
                    required
                >

                <button
                    class="login-btn"
                    type="submit"
                    name="update">
                    Update Student
                </button>
            </form>

            <form method="post" onsubmit="return confirm('Are you sure you want to delete this student?');">
                <button
                    class="signup-btn"
                    style="background: #ff6b6b; color: white;"
                    type="submit"
                    name="delete">
                    Delete Student
                </button>
            </form>

            <button
                class="back-btn"
                onclick="window.location='frontend.php'">
                Back to Student List
            </button>
        </div>
    </div>

    <footer class="site-footer">
        Student Management System © 2026
    </footer>
</body>
</html>
