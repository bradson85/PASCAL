<?php
require_once("../db/dbconfig.php");

try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

    // prepare sql and bind parameters
    $sql = $pdo->prepare("INSERT INTO Terms (id, word, defintion, catID) 
    VALUES (:id, :word, :definition,:catID)");
     $sql->bindParam(':id', $id);
     $sql->bindParam(':word', $word);
     $sql->bindParam(':definitniion', $definition);
     $sql->bindParam(':catID', $catID);

    // insert a row
    $id = "5";
    $word = "test";
    $defintion = "def";
    $catID = "1";
    $sql->execute();


    echo "Saved successfully";
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
?>
