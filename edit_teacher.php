<?php
session_start();

require_once 'database.php';

$error = "";
$success = "";

if (($_SESSION['role'] ?? 'gebruiker') !== 'admin') {
    header("Location: teachers.php");
    exit;
}

$teachers = new Teachers();

$teacher_id = intval($_GET['id'] ?? 0);

$teacher = $teachers->getById($teacher_id);

if (!$teacher) {
    header("Location: teachers.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $newId = intval($_POST['id']);

    if ($newId != $teacher_id && $teachers->idExists($newId)) {

        $error = "ID already exists!";

    } else {

        $result = $teachers->update(
            $_POST['naam'],
            $_POST['email'],
            $_POST['wachtwoord'],
            $_POST['role'],
            $newId
        );

        if (strpos($result, "successfully") !== false) {

            if ($newId != $teacher_id) {
                mysqli_query(
                    $teachers->db->conn,
                    "UPDATE users SET id = $newId WHERE id = $teacher_id"
                );
            }

            $success = "Teacher updated successfully!";
            $teacher = $teachers->getById($newId);
            $teacher_id = $newId;

        } else {
            $error = $result;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

    if ($teachers->deleteById($teacher_id)) {

        header("Location: teachers.php");
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
    <title>Edit Teacher</title>
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

        <h1>Edit Teacher</h1>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p class="success">
                <?php echo htmlspecialchars($success); ?>
            </p>
        <?php endif; ?>

        <form method="post">

            <input
                type="text"
                name="id"
                placeholder="Teacher ID"
                value="<?php echo htmlspecialchars($teacher['id']); ?>"
                required
            >

            <input
                type="text"
                name="naam"
                placeholder="Name"
                value="<?php echo htmlspecialchars($teacher['naam']); ?>"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email"
                value="<?php echo htmlspecialchars($teacher['email']); ?>"
                required
            >

            <select
                name="role"
                style="width:100%; padding:16px; margin-bottom:24px; border:none; border-radius:12px;"
                required
            >
                <option value="gebruiker"
                    <?php if ($teacher['role'] === 'gebruiker') echo 'selected'; ?>>
                    gebruiker
                </option>

                <option value="moderator"
                    <?php if ($teacher['role'] === 'moderator') echo 'selected'; ?>>
                    moderator
                </option>

                <option value="admin"
                    <?php if ($teacher['role'] === 'admin') echo 'selected'; ?>>
                    admin
                </option>
            </select>

            <button
                class="login-btn"
                type="submit"
                name="update">
                Update Teacher
            </button>

        </form>

        <form
            method="post"
            onsubmit="return confirm('Are you sure you want to delete this teacher?');"
        >
            <button
                class="signup-btn"
                style="background:#ff6b6b;color:white;"
                type="submit"
                name="delete">
                Delete Teacher
            </button>
        </form>

        <button
            class="back-btn"
            onclick="window.location='teachers.php'">
            Back to Teacher List
        </button>

    </div>

</div>

<footer class="site-footer">
    Student Management System © 2026
</footer>

</body>
</html>