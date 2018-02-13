<?php

// this returns all the categories in the database and places them inside of an a select option html dropdown
require_once("../dbconfig.php");
$schools ="";
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
// query to get all categries for drop down menu
    $sql = "SELECT * FROM schools";
    $result = $pdo->query($sql);
    while($row = $result->fetch(PDO::FETCH_ASSOC) ){
          $schoolName = $row['name'];   
          $schools .= "<option value = \"$schoolName\"> $schoolName</option>"; //html     
    }
   
    // query for the rest of the table
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
    $sql = 'SELECT * From classes';
    $result = $pdo->query($sql);
    
     while($row = $result->fetch(PDO::FETCH_ASSOC) ){
      $name= $row['name'];
      $gradeLevel = $row['gradeLevel'];
      $ID = $row['ID'];
      echo "<tr><td style='display:none;'>$ID</td> 
     <td contenteditable= 'true'> $name </td>  
     <td><select class=\"form-control\" id=\"selLev\"><<option value = \"0\"> --Select Level--</option>
     <option value = \"1\"> 1 </option>
     <option value = \"2\"> 2 </option>
     <option value = \"3\"> 3 </option>
     <option value = \"4\"> 4 </option>
     <option value = \"5\"> 5 </option>
     <option value = \"5\"> 6 </option>
     <option value = \"6\"> 7 </option></select></td>
      <td>
      <select class='form-control' id='selschool'><<option value = \"0\"> --Select School--</option> $schools
     </select></td> 
       <td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>" ; // html stuff

     }
        $pdo = null;
        } catch (PDOException $e) {
        die( $e->getMessage() );
        } 
    
   
?>