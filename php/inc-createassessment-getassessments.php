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
           $start = new DateTime($row['start_date']);
           $start = $start->format('Y-m-d');
           $startInput = "<input type=\"date\" id=\"start_date\" value=\"$start\">";
           $end = new DateTime($row['end_date']);
           $end = $end->format('Y-m-d');
           $endInput = "<input type=\"date\" id=\"end_date\" value=\"$end\">";
          $splitString = explode(',',matchIDToCat($catID));  
           
         echo ("<tr><td id=\"ID\" value=\"$ID\">$ID</td>  
         <td>$startInput</td>
         <td>$endInput</td>   
         <td>" . $splitString[0] . " - Level " . $splitString[1] . "</td>" .
         "<td> <button class=\"btn\" id=\"delete\" value=\"$ID\">Delete</button></td>" .
         "</tr>");

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 
    

?>