<?php 
require_once 'database.php';

class user extends Database {

// ADD 
 public function add($name, $email, $password) {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (naam, email, wachtwoord) VALUES ('$name', '$email', '$password')";

        if (mysqli_query($this->conn, $sql)) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }

    // DELETE

    public function delete($id, $voornaam, $achternaam, $klas) {
        $sql = "DELETE FROM studenten WHERE id = '$id' AND voornaam = '$voornaam' AND achternaam = '$achternaam' AND klas = '$klas'";

        if (mysqli_query($this->conn, $sql)) {
            return "Record deleted successfully";
        } else {
            return "Error deleting record: " . mysqli_error($this->conn);
        }
    }

   // UPDATE 
    public function update($id, $voornaam, $achternaam, $klas) {
        $sql = "UPDATE studenten SET voornaam = '$voornaam', achternaam = '$achternaam', klas = '$klas' WHERE id = '$id'";

        if (mysqli_query($this->conn, $sql)) {
            return "Record updated successfully";
        } else {
            return "Error updating record: " . mysqli_error($this->conn);
        }
    }


// LOGIN

public function login($naam, $password)
{
    $naam = mysqli_real_escape_string($this->conn, $naam);

    $sql = "SELECT * FROM users WHERE naam = '$naam'";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['wachtwoord'])) {

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['naam'] = $user['naam'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'] ?? 'gebruiker';
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
    header("Location: index.php");
}

public function usernameExists($name)
{
    $name = mysqli_real_escape_string($this->conn, $name);
    $sql = "SELECT id FROM users WHERE naam = '$name'";
    $result = mysqli_query($this->conn, $sql);
    return mysqli_num_rows($result) > 0;
}
}



  

?>