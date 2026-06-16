<?php
session_start();

require_once 'database.php';
require_once 'user.php';

$error = "";
$success = "";

if (($_SESSION['role'] ?? 'gebruiker') === 'gebruiker') {
    header("Location: index.php");
    exit;
}

$studenten = new Studenten();

$student_id = intval($_GET['id'] ?? 0);

$student = $studenten->getById($student_id);

if (!$student) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $newId = intval($_POST['id']);

    if ($newId != $student_id && $studenten->idExists($newId)) {

        $error = "ID already exists!";

    } else {

        $ok = $studenten->update(
            $student_id,
            $newId,
            $_POST['voornaam'],
            $_POST['achternaam'],
            $_POST['klas']
        );

        if ($ok) {
            $success = "Student updated successfully!";
            $student = $studenten->getById($newId);
            $student_id = $newId;
        } else {
            $error = "Update failed.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

    if ($studenten->delete($student_id)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Delete failed.";
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
                onclick="window.location='index.php'">
                Back to Student List
            </button>
        </div>
    </div>

    <footer class="site-footer">
        Student Management System © 2026
    </footer>
</body>
</html>
