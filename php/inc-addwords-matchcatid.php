<?php
require_once("../db/dbconfig.php");


function matchCatID($categoryName){
    
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

 $sql = $pdo->prepare("SELECT ID From categories WHERE name = :catName");

 $sql->bindParam(':catName', $catName, PDO::PARAM_INT);
     $catName = $categoryName;
     $sql->execute();
     $row = $sql->fetchAll(PDO::FETCH_ASSOC) ;
          $value= $row["ID"];     

}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
return $value;
}

?>