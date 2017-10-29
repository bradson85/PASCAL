<?php
require_once("../db/dbconfig.php");


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
    $sql = $pdo->prepare("DELETE FROM terms
    WHERE ID = :id");
     $sql->bindParam(':id', $id);
    $id = $data;
    $sql->execute();
     
    echo "Deleted Item Successfully";
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;

?>