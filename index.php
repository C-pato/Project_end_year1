<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

$db = new Database();
$conn = $db->connect();

$selectedClass = $_GET['klas'] ?? 'all';

$where = "";

if ($selectedClass !== 'all') {
    $selectedClass = mysqli_real_escape_string($conn, $selectedClass);
    $where = "WHERE klas = '$selectedClass'";
}

function activeClass($class, $selected) {
    return $class === $selected ? "background: rgba(255,255,255,0.25);" : "";
}

$sql = "SELECT id, voornaam, achternaam, klas FROM studenten $where";
$result = mysqli_query($conn, $sql);

$error = "";
$success = "";
$userRole = $_SESSION['role'] ?? 'gebruiker';

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
        <div class="header-left">
            <?php if (
                isset($_SESSION['role']) &&
                ($_SESSION['role'] === 'moderator' || $_SESSION['role'] === 'admin')
            ): ?>
                <button
                    class="nav-btn"
                    onclick="window.location='teachers.php'">
                    Teachers
                </button>
            <?php endif; ?>
        </div>

        <div class="site-title">
            Student Management System
        </div>

        <div class="nav-buttons">

            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="nav-dropdown">
                    <button class="nav-btn dropdown-toggle" onclick="toggleDropdown()">
                        Account ▾
                    </button>

                    <div id="dropdown-menu" class="dropdown-menu">
                        <button onclick="window.location='login.php'">Login</button>
                        <button onclick="window.location='signup.php'">Sign Up</button>
                    </div>
                </div>

            <?php else: ?>

                <button
                    class="nav-btn"
                    onclick="window.location='logout.php'">
                    Logout
                </button>

            <?php endif; ?>

        </div>
    </header>

        <div class="page-center">

            <div class="board-container">

                <div class="class-filter">
                    <button class="nav-btn" style="<?= activeClass('all', $selectedClass) ?>"
                    onclick="window.location='index.php?klas=all'">
                        All classes
                    </button>

                    <button class="nav-btn" style="<?= activeClass('SD1A', $selectedClass) ?>"
                    onclick="window.location='index.php?klas=SD1A'">
                        SD1A
                    </button>

                    <button class="nav-btn" style="<?= activeClass('SD1B', $selectedClass) ?>"
                    onclick="window.location='index.php?klas=SD1B'">
                        SD1B
                    </button>
                </div>

                <div class="student-header">
                    <h1>Student list</h1>  
                    <?php if (isset($_SESSION['role'])): ?>
                    <?php endif; ?>
                </div>

                <table class="student-table">
                    <tr>
                        <th>ID</th>
                        <th>Voornaam</th>
                        <th>Achternaam</th>
                        <th>Klas</th>
                        <?php if ($userRole !== 'gebruiker'): ?>
                            <th>edit</th>
                        <?php endif; ?>
                    </tr>

                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['voornaam'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['achternaam'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($row['klas'] ?? ''); ?></td>
                            <?php if ($userRole !== 'gebruiker'): ?>
                                <td>
                                    <a class='action-btn' href="bewerken.php?id=<?php echo $row['id']; ?>">Edit</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </table>

                <div class="add-student-section">
                    <?php if ($userRole !== 'gebruiker'): ?>
                        <button class="add-student-btn" onclick="toggleAddForm()">+ Add Student</button>
                    <?php endif; ?>
                    
                    <?php if ($userRole !== 'gebruiker'): ?>
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
                    <?php endif; ?>
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

            function toggleDropdown() {
                const menu = document.getElementById("dropdown-menu");
                menu.classList.toggle("show");
            }

            window.onclick = function(event) {
                if (!event.target.matches('.dropdown-toggle')) {
                    const menu = document.getElementById("dropdown-menu");
                    if (menu) menu.classList.remove("show");
                }
            }
        </script>


        <footer class="site-footer">
            Student Management System © 2026
        </footer>
</body>
</html>
