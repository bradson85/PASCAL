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
 $sql = $pdo->prepare("SELECT * FROM classes WHERE ID =:ID");
 $sql->bindParam(':ID', $ID);
    $ID = trim($TableData[$i]['ID']," ");          // Trim To get rid of whitespace at begining and end 
    $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
       if(!$row) { // if row doesnt exist in the database then insert below
                                      // level is second of split screen

        // now check if class exists                              
        $sql = $pdo->prepare("SELECT name FROM classes WHERE name =:className");
         $sql->bindParam(':className', $className);
         $className = trim($TableData[$i]['className']," ");          // Trim To get rid of whitespace at begining and end
          $sql->execute();
          $row = $sql->fetch(PDO::FETCH_ASSOC) ;
          if($row){ // if there is a new entry and the class name exists.
              //do nothing 
          }else{   // insert new name
        $sql = $pdo->prepare("INSERT INTO classes (name, gradeLevel, schoolID)
         Values (:className, :gradeLevel, :schoolName)");
        $sql->bindParam(':schoolName', $schoolName);
        $sql->bindParam(':className', $className);
        $sql->bindParam(':gradeLevel', $gradeLevel);
        $schoolName = trim(getSchoolID($TableData[$i]['schoolName'])," ");
        $className = trim($TableData[$i]['className']," "); 
        $gradeLevel = trim($TableData[$i]['gradeLevel']," ");           // Trim To get rid of whitespace at begining and end
       $sql->execute();
         $success = 1;
            
        } }
        else{// when there already exist and item in DB
            //update existing rows.
                 
            //category is selected
           $sql = $pdo->prepare("UPDATE classes SET name =:className, gradeLevel = :gradeLevel,
           schoolID = :schoolName WHERE ID = :ID");
           $sql->bindParam(':ID', $ID);
           $sql->bindParam(':schoolName', $schoolName);
           $sql->bindParam(':className', $className);
           $sql->bindParam(':gradeLevel', $gradeLevel);
           $ID = trim($TableData[$i]['ID']," ");
           $schoolName = trim(getSchoolID($TableData[$i]['schoolName'])," ");
           $className = trim($TableData[$i]['className']," "); 
           $gradeLevel = trim($TableData[$i]['gradeLevel']," ");        //Trim to clean up leading and trailing whitespace.
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

// function to find what category name
function getSchoolID($schoolName){
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    $sql = $pdo->prepare("SELECT ID FROM schools Where name = :schoolName");
    $sql->bindParam(':schoolName', $schoolName);        
       $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC) ;
        return $row["ID"];
}

?>
