<?php
require_once("../db/dbconfig.php");
include("inc-createassessment-functions.php");
   
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
     $sql = ("SELECT ID, start_date, end_date,catID FROM assessments");
    
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $catID = $row['catID'];
           $ID = $row['ID'];
           $start = $row['start_date'];
           $end = $row['end_date'];
          $splitString = explode(',',matchIDToCat($catID));  
        
         echo ("<tr><td td contenteditable= 'true'>$ID</td>  
         <td contenteditable= 'true'> $start</td>
         <td contenteditable= 'true'> $end</td>   
         <td contenteditable= 'true'> $splitString[0] - $splitString[1]</td> 
          </tr>");

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 
    

?>