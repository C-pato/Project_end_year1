<?php

class Database {

    public $hostname = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "sandbox1";
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
    if (
        isset($_GET['pagina']) &&
        file_exists($_GET['pagina'] . '.php')
    ) {
        include $_GET['pagina'] . '.php';
    } else {
        include '404.php';
    }
}

}
?>