<?php

class Database {

    public $hostname = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "eindproject";
    public $conn;

    public function connect() {
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
        
                
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        } 
        // else {
        //     echo "Connected successfully";
        // }
        return $this->conn;
    }


public function trigger()
{
    if (!isset($_GET['pagina'])) {
        include "index.php";
        return;
    }

    if (file_exists($_GET['pagina'] . ".php")) {
        include $_GET['pagina'] . ".php";
    } else {
        include "404.php";
    }
}
// {
//     if (
//         isset($_GET['pagina']) &&
//         file_exists($_GET['pagina'] . '.php')
//     ) {
//         include $_GET['pagina'] . '.php';
//     } else {
//         include '404.php';
//     }
// }
}


class Studenten
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->connect();
    }

    public function read()
    {
        $sql = "SELECT id, voornaam, achternaam, klas FROM studenten";
        return mysqli_query($this->db->conn, $sql);
    }

    public function getById($id)
    {
        $id = intval($id);

        $sql = "SELECT id, voornaam, achternaam, klas
                FROM studenten
                WHERE id = $id";

        $result = mysqli_query($this->db->conn, $sql);

        return mysqli_fetch_assoc($result);
    }

    public function idExists($id)
    {
        $id = intval($id);

        $sql = "SELECT id FROM studenten WHERE id = $id";
        $result = mysqli_query($this->db->conn, $sql);

        return mysqli_num_rows($result) > 0;
    }

    public function update($oldId, $newId, $voornaam, $achternaam, $klas)
    {
        $oldId = intval($oldId);
        $newId = intval($newId);

        $voornaam = mysqli_real_escape_string($this->db->conn, $voornaam);
        $achternaam = mysqli_real_escape_string($this->db->conn, $achternaam);
        $klas = mysqli_real_escape_string($this->db->conn, $klas);

        $sql = "
            UPDATE studenten
            SET
                id = $newId,
                voornaam = '$voornaam',
                achternaam = '$achternaam',
                klas = '$klas'
            WHERE id = $oldId
        ";

        return mysqli_query($this->db->conn, $sql);
    }

    public function delete($id)
    {
        $id = intval($id);

        $sql = "DELETE FROM studenten WHERE id = $id";

        return mysqli_query($this->db->conn, $sql);
    }
}


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
            if (mysqli_query($this->db->conn, $sql)) {
                return "Teacher added successfully";
            }

            return "Error: " . mysqli_error($this->db->conn);
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

    public function getById($id)
{
    $id = intval($id);

    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($this->db->conn, $sql);

    return mysqli_fetch_assoc($result);
}

public function idExists($id)
{
    $id = intval($id);

    $sql = "SELECT id FROM users WHERE id = $id";
    $result = mysqli_query($this->db->conn, $sql);

    return mysqli_num_rows($result) > 0;
}

public function deleteById($id)
{
    $id = intval($id);

    $sql = "DELETE FROM users WHERE id = $id";

    return mysqli_query($this->db->conn, $sql);
}
}
?>