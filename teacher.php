<?php



class Teachers
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->connect();
    }

    public function teachers()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin')
        ) {
            die('Geen toegang');
        }

        $sql = "SELECT id, naam, email, wachtwoord, role FROM users";
        return mysqli_query($this->db->conn, $sql);
    }


    // TEACHER ADD
    public function add($naam, $email, $wachtwoord, $role)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] != 'admin')
        ) {
            return "Geen toestemming om leeraar toe te voegen.";
        }

        $naam = mysqli_real_escape_string($this->db->conn, $naam);
        $email = mysqli_real_escape_string($this->db->conn, $email);
        $wachtwoord = mysqli_real_escape_string($this->db->conn, $wachtwoord);
        $role = mysqli_real_escape_string($this->db->conn, $role);

        $sql = "INSERT INTO users (naam, email, wachtwoord, role)
            VALUES ('$naam', '$email', '$wachtwoord', '$role')";

        if (mysqli_query($this->db->conn, $sql)) {
            header("Location: frontend.php?success=1");
            exit;
        }

        return "Error: " . mysqli_error($this->db->conn);
    }

    public function delete($naam, $email)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] != 'admin')
        ) {
            return "Geen toestemming om teachers te verwijderen.";
        }
        $sql = "DELETE FROM users WHERE naam = '$naam' AND email = '$email'";

        if (mysqli_query($this->db->conn, $sql)) {
            return "Record deleted successfully";
        } else {
            return "Error deleting record: " . mysqli_error($this->db->conn);
        }
    }
    public function update($naam, $email, $wachtwoord, $role, $id)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] != 'admin')
        ) {
            return "Geen toestemming om teachers te wijzigen.";
        }

        $sql = "UPDATE users SET naam = '$naam', email = '$email', wachtwoord = '$wachtwoord', role = '$role' WHERE id = '$id'";

        if (mysqli_query($this->db->conn, $sql)) {
            return "Record updated successfully";
        } else {
            return "Error updating record: " . mysqli_error($this->db->conn);
        }
    }
}
?>