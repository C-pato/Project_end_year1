<?php
session_start();

require_once 'database.php';
require_once 'Teachers.php';

$teachers = new Teachers();
$result = $teachers->teachers();

$userRole = $_SESSION['role'] ?? 'gebruiker';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {

    if ($userRole !== 'admin') {

        $error = "Only admins can add teachers.";

    } else {

        $resultCheck = $teachers->getById(intval($_POST['id']));

        if ($resultCheck) {

            $error = "ID already exists!";

        } else {

            $resultAdd = $teachers->add(
                $_POST['naam'],
                $_POST['email'],
                $_POST['wachtwoord'],
                $_POST['role']
            );

            if (strpos($resultAdd, "Error") === false) {
                $success = "Teacher added successfully!";
            } else {
                $error = $resultAdd;
            }
        }
    }

    $result = $teachers->teachers();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="site-header">

    <div class="header-left">
        <button
            class="nav-btn"
            onclick="window.location='index.php'">
            Students
        </button>
    </div>

    <div class="site-title">
        Student Management System
    </div>

    <div class="nav-buttons">
        <button
            class="nav-btn"
            onclick="window.location='logout.php'">
            Logout
        </button>
    </div>

</header>

<div class="page-center">

    <div class="board-container">

        <div class="student-header">
            <h1>Teacher List</h1>
        </div>

        <table class="student-table">

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>

                <?php if ($userRole === 'admin'): ?>
                    <th>Edit</th>
                <?php endif; ?>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td><?php echo $row['id']; ?></td>

                    <td>
                        <?php echo htmlspecialchars($row['naam']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['role']); ?>
                    </td>

                    <?php if ($userRole === 'admin'): ?>
                        <td>
                            <a
                                class="action-btn"
                                href="edit_teacher.php?id=<?php echo $row['id']; ?>">
                                Edit
                            </a>
                        </td>
                    <?php endif; ?>

                </tr>

            <?php endwhile; ?>

        </table>
            <div class="add-student-section">

                <?php if ($userRole === 'admin'): ?>

                    <button
                        class="add-student-btn"
                        onclick="toggleTeacherForm()">
                        + Add Teacher
                    </button>

                <?php endif; ?>

            </div>

            <?php if ($userRole === 'admin'): ?>

            <form
                id="teacher-form"
                method="post"
                style="display:none; margin-top:20px; padding:20px; border:2px dashed rgba(255,255,255,0.5); border-radius:10px;"
            >

                <?php if (!empty($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <p class="success"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>

                <input
                    type="text"
                    name="id"
                    placeholder="Teacher ID"
                    required
                >

                <input
                    type="text"
                    name="naam"
                    placeholder="Name"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                >

                <input
                    type="password"
                    name="wachtwoord"
                    placeholder="Password"
                    required
                >

                <select
                    name="role"
                    required
                    style="width:100%; padding:16px; margin-bottom:20px; border-radius:10px;"
                >
                    <option value="gebruiker">gebruiker</option>
                    <option value="moderator">moderator</option>
                    <option value="admin">admin</option>
                </select>

                <button
                    type="submit"
                    name="add_teacher"
                    class="login-btn">
                    Add Teacher
                </button>

                <button
                    type="button"
                    class="back-btn"
                    onclick="toggleTeacherForm()">
                    Cancel
                </button>

            </form>

            <?php endif; ?>

    </div>

</div>

<footer class="site-footer">
    Student Management System © 2026
</footer>

<script>
function toggleTeacherForm() {

    const form = document.getElementById('teacher-form');

    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>

</body>
</html>