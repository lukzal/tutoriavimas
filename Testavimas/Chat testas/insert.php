<?php


class Insert{
    
function insertMessage($message){
    include_once 'connect_DB.php';
    $db = new connect_DB();
    if(isset($message)){
        
        $sql = "INSERT INTO messages (message) VALUES ('$message')";
        $db->getDB()->query($sql);
    }
}
}
?>