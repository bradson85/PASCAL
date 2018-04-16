<?php
require_once("../db/dbconfig.php");
include("inc-createassessment-functions.php");
include("inc-createassessment-getcategories.php");
   
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
           $start = new DateTime($row['start_date']);
           $start = $start->format('Y-m-d');
           $startInput = "<input type=\"date\" value=\"$start\">";
           $end = new DateTime($row['end_date']);
           $end = $end->format('Y-m-d');
           $endInput = "<input type=\"date\" value=\"$end\">";
          $splitString = explode(',',matchIDToCat($catID));  
        //   $splitString[0] - $splitString[1]
         echo ("<tr><td>$ID</td>  
         <td>$startInput</td>
         <td>$endInput</td>   
         <td>" . GetCategories("$ID", $catID) . "</td>" .
          "</tr>");

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 
    

?>