<?php
require_once("../db/dbconfig.php");
include "inc-addwords-functions.php";

$success = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $TableData = json_decode(stripslashes($_POST['data']),true);
}
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    for ($i=0; $i < count($TableData); $i++) {
 $sql = $pdo->prepare("SELECT * From terms WHERE ID = :ID");
 $sql->bindParam(':ID', $ID,PDO::PARAM_INT);
  
    $ID = $TableData[$i]['ID'];
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
       if(!$row) {
        $splitString = explode(' ',$TableData[$i]['category']);
        $category =  $splitString[0];
        $level = $splitString[1];

        if($category == "0"){ 
           $success= 0;
        }
    else{
        $sql = $pdo->prepare("INSERT INTO terms (name, definition, catID)
         Values (:word , :definition, :catID)");
        $sql->bindParam(':word', $word);
       $sql->bindParam(':definition', $def);
       $sql->bindParam(':catID', $catID); 
        $word = $TableData[$i]['word'];
        $def = $TableData[$i]['definition'];
        $category =  $splitString[0];
        $level = $splitString[1];
        $catID = matchCatID($category, $level);
       $sql->execute();
      
            }
       }
        else{
            $splitString = explode(' ',$TableData[$i]['category']);
            $category =  $splitString[0];
            $level = $splitString[1];

            if($category == "0"){ 
                $success = 0;
            }
                else{
           $sql = $pdo->prepare("UPDATE terms SET name =:word , definition = :definition, catID = :catID WHERE ID = :IDs");
            $sql->bindParam(':IDs', $ID2,PDO::PARAM_INT);
            $sql->bindParam(':word', $word);
           $sql->bindParam(':definition', $def);
           $sql->bindParam(':catID', $catID); 
           $ID2 = $TableData[$i]['ID'];
            $word = $TableData[$i]['word'];
            $def = $TableData[$i]['definition'];
            $category =  $splitString[0];
            $level = $splitString[1];
            $catID = matchCatID($category, $level);
           $sql->execute();

           
            }
        }
 }
}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
       
    echo $success;

?>
