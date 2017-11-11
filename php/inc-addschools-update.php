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
 $sql = $pdo->prepare("SELECT * FROM schools WHERE ID =:ID");
 $sql->bindParam(':ID', $ID);
 
    $ID = trim($TableData[$i]['ID']," ");          // Trim To get rid of whitespace at begining and end
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
       if(!$row) { // if row doesnt exist in the database then insert below
                                      // level is second of split screen

        // now check if school exists                              
        $sql = $pdo->prepare("SELECT name FROM schools WHERE name =:schoolName");
         $sql->bindParam(':schoolName', $schoolName);
         $schoolName = trim($TableData[$i]['schoolName']," ");          // Trim To get rid of whitespace at begining and end
          $sql->execute();
          $row = $sql->fetch(PDO::FETCH_ASSOC) ;
          if($row){ // if there is a new entry and the schoool name exists.
              //do nothing 
          }else{   // insert new name
    
        $sql = $pdo->prepare("INSERT INTO schools (name)
         Values (:schoolName)");
        $sql->bindParam(':schoolName', $schoolName);
        $schoolName = trim($TableData[$i]['schoolName']," ");            // Trim To get rid of whitespace at begining and end
       $sql->execute();
         $success = 1;
            
        } }
        else{// when there already exist and item in DB
            //update existing rows.
                 
            //category is selected
           $sql = $pdo->prepare("UPDATE schools SET name =:schoolName WHERE ID = :ID");
           $sql->bindParam(':ID', $ID);
           $sql->bindParam(':schoolName', $schoolName);
           $ID = trim($TableData[$i]['ID']," ");
            $schoolName = trim($TableData[$i]['schoolName']," ");        //Trim to clean up leading and trailing whitespace.
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
