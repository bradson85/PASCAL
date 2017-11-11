<?php
// this file updates the database to match the table recored on the webpage
require_once("../dbconfig.php");

$success = 1; // used to detect if a category is not selected from dropdown menu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field and convert jason to array
    $TableData = json_decode(stripslashes($_POST['data']),true);
}
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    for ($i=0; $i < count($TableData); $i++) {
        //query to select everything from database and see if current id exists in the table.
 $sql = $pdo->prepare("SELECT * FROM categories WHERE ID =:ID");
 $sql->bindParam(':ID', $ID);
    $ID = $TableData[$i]['ID']; // id from array
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
       if(!$row) { // if row doesnt exist in the database then insert below
                                      // level is second of split screen
      
    $sql = $pdo->prepare("SELECT * FROM categories WHERE name =:catName And level=:level");
    $sql->bindParam(':catName', $catName);
    $sql->bindParam(':level', $level); 
    $catName = trim($TableData[$i]['catName']," ");            // Trim To get rid of whitespace at begining and end
    $level = trim($TableData[$i]['level']," ");
     $sql->execute();
    $row = $sql->fetch(PDO::FETCH_ASSOC) ;

    if($row) { //if there is a new entry and the name  and level exists already
         // do nothing 
    }else{
        $sql = $pdo->prepare("INSERT INTO categories (name, level)
         Values (:name , :level)");
        $sql->bindParam(':name', $catName);
       $sql->bindParam(':level', $level); 
        $catName = trim($TableData[$i]['catName']," ");            // Trim To get rid of whitespace at begining and end
        $level = trim($TableData[$i]['level']," ");      // caused by html.
       $sql->execute();
         $success = 1;
            
       }}
        else{// when there already exist and item in DB
            //update existing rows.
                 
            //category is selected
           $sql = $pdo->prepare("UPDATE categories SET name =:catName, level =:level WHERE ID = :ID");
           $sql->bindParam(':ID', $ID2);
            $sql->bindParam(':catName', $catName);
           $sql->bindParam(':level', $level);
           $ID2 = trim($TableData[$i]['ID']," ");
            $catName = trim($TableData[$i]['catName']," ");        //Trim to clean up leading and trailing whitespace.
            $level = trim($TableData[$i]['level']," ");
           $sql->execute();           
           $success = 1;
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
