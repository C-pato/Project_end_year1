<?php 
require_once 'backend.php';

class user extends Database {
 public function add($name, $email, $password) {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);

        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if (mysqli_query($this->conn, $sql)) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }


    public function delete($name, $email, $password) {
        $sql = "DELETE FROM users WHERE name = '$name' AND email = '$email' AND password = '$password'";

        if (mysqli_query($this->conn, $sql)) {
            return "Record deleted successfully";
        } else {
            return "Error deleting record: " . mysqli_error($this->conn);
        }
    }
    public function update($name, $email, $password) {
        $sql = "UPDATE users SET password = '$password' WHERE name = '$name' AND email = '$email'";

        if (mysqli_query($this->conn, $sql)) {
            return "Record updated successfully";
        } else {
            return "Error updating record: " . mysqli_error($this->conn);
        }
    }
}       

?>