<?php
require_once("../dbconfig.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $data = $_POST['data'];
}

// Sql query to delete the terms from database.
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    // prepare sql and bind parameters
    // example query. updates if an update has happend INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
    $sql = $pdo->prepare("DELETE FROM categories
    WHERE ID = :id");
     $sql->bindParam(':id', $id);
    $id = $data;
    $sql->execute();
     
    echo "Deleted Item Successfully";
    }
catch(PDOException $e)
    {
     $error =  $e->getCode();
  if($error == 23000){
    echo "Cannot delete Category. Terms Exist in the database 
    that depend on this category. Delete all the Terms associated with
    this category first. ";

  }else echo "Error". $e->getMessage();
    }
$pdo = null;

?>