<?php 
class connect_DB{
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $databaseName = "test";
    
    protected $database = null;
    
    function __construct(){
        $this->database = new mysqli($this->host, $this->user, $this->password, $this->databaseName);
        if($this->database->connect_errno)
            echo $this->database->connect_errno;
        
    }
    
    function getDB(){
        return $this->database;
    }
}
?>