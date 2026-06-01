<?php

class Database {  
 public function connect() {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "sandbox1";

$conn = mysqli_connect($hostname, $username, $password, $database);
    }
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    else if ($conn) {
        echo "Connected successfully";
    }

} 
?>