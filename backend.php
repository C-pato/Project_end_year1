<?php

class Database {

    public $hostname = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "sandbox1";
    public $conn;

    public function connect() {
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
        return $this->conn;
    }

    public function adduser($name, $email) {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        
        $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";

        if (mysqli_query($this->conn, $sql)) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }
}
?>