<?php
require_once("../db/dbconfig.php");
include "inc-addwords-matchcatid.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $TableData = json_decode(stripslashes($_POST['data']),true);
}

try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    // prepare sql and bind parameters
    // example query. updates if an update has happend INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
    $sql = $pdo->prepare("INSERT INTO terms (name, definition, catID) 
    VALUES ( :word, :definition, :catID) 
    ON DUPLICATE KEY UPDATE   name = :word, definition = :definition, catID = :catID");
     $sql->bindParam(':word', $word);
     $sql->bindParam(':definition', $definition);
     $sql->bindParam(':catID', $catID);
    
    for ($i=0; $i < count($TableData) ; $i++) { 
    $word = $TableData[$i]["word"];
    $definition = $TableData[$i]["definition"];
    $catID= matchCatID($TableData[$i]["category"]);
    $sql->execute();
     }

    echo "Saved successfully";
    }
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;

?>
