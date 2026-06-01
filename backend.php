<?php

class Database {  
 public function connect() {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "sandbox1";

$conn = mysqli_connect($hostname, $username, $password, $database);
    }
} 
?>