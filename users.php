<?php
require_once 'Database.php';

class user
{
    public $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->db->connect();
    }
    // ADD 
    public function add($voornaam, $achternaam)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'moderator')
        ) {
            return "Geen toestemming om studenten toe te voegen.";
        }

        $voornaam = mysqli_real_escape_string($this->db->conn, $voornaam);
        $achternaam = mysqli_real_escape_string($this->db->conn, $achternaam);

        $sql = "INSERT INTO studenten (voornaam, achternaam)
            VALUES ('$voornaam', '$achternaam')";

        if (mysqli_query($this->db->conn, $sql)) {
            header("Location: frontend.php?success=1");
            exit;
        }

        return "Error: " . mysqli_error($this->db->conn);
    }

    // DELETE

    public function delete($voornaam, $achternaam)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'moderator')
        ) {
            return "Geen toestemming om studenten te verwijderen.";
        }
        $sql = "DELETE FROM studenten WHERE voornaam = '$voornaam' AND achternaam = '$achternaam'";

        if (mysqli_query($this->db->conn, $sql)) {
            return "Record deleted successfully";
        } else {
            return "Error deleting record: " . mysqli_error($this->db->conn);
        }
    }

    // UPDATE 
    public function update($voornaam, $achternaam, $id)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['role']) ||
            ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'moderator')
        ) {
            return "Geen toestemming om studenten te wijzigen.";
        }

        $sql = "UPDATE studenten SET voornaam = '$voornaam', achternaam = '$achternaam' WHERE id = '$id'";

        if (mysqli_query($this->db->conn, $sql)) {
            return "Record updated successfully";
        } else {
            return "Error updating record: " . mysqli_error($this->db->conn);
        }
    }


    // LOGIN

    public function login($naam, $password)
    {
        $naam = mysqli_real_escape_string($this->db->conn, $naam);

        $sql = "SELECT * FROM users WHERE naam = '$naam'";
        $result = mysqli_query($this->db->conn, $sql);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['wachtwoord'])) {

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['naam'] = $user['naam'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['ingelogd'] = true;

                return true;
            }
        }

        return false;
    }

    // LOGOUT
    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
        header("Location: frontend.php");
    }
}

?>