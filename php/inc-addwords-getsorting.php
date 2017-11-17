<?php

// this returns all the categories in the database and places them inside of an a select option html dropdown
require_once("../dbconfig.php");

// start html selelct class
   $selectString = "<td><select class=\"form-control\" id=\"sort\"><<option value=\"All\">All Words</option>";

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query all the categories
     $sql = ("SELECT * FROM categories");
    
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $catID = $row['ID'];
           $catName = $row['name'];
           $level = $row['level']; 
// more of the html placing the variables inside
//   . here for concatination you concat with . not + in php;
           $selectString.= "<option value = \"$catName $level\"> $catName - Level $level</option>";

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 // close the html brackets
    $selectString.= "</select></td>";
    echo $selectString; // return string.

?>