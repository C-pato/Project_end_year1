<?php


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
        $sql = "SELECT id, voornaam, achternaam FROM studenten";
        $result = mysqli_query($this->db->conn, $sql);

        return $result;
    }

}


?>