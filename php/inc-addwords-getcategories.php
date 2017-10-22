<?php
require_once("../db/dbconfig.php");


$json;

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
     $sql = ("SELECT categories.name AS catName, categories.ID AS catID, categories.level From categories");
    
     $result = $pdo->query($sql);
     $row = $result->fetchAll(PDO::FETCH_ASSOC) ;

    $json=json_encode($row);     
    
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
   echo ($json);
    

?>