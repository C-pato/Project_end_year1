<?php 
require_once 'backend.php';

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

    public function delete($name, $email, $password) {
        $sql = "DELETE FROM users WHERE naam = '$name' AND email = '$email' AND wachtwoord = '$password'";

        if (mysqli_query($this->conn, $sql)) {
            return "Record deleted successfully";
        } else {
            return "Error deleting record: " . mysqli_error($this->conn);
        }
    }

   // UPDATE 
    public function update($name, $email, $password) {
        $sql = "UPDATE users SET wachtwoord = '$password' WHERE naam = '$name' AND email = '$email'";

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