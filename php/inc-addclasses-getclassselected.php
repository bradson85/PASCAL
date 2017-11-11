<?php

// this returns all the categories in the database and places them inside of an a select option html dropdown
require_once("../dbconfig.php");

// start html selelct class
$selectString = "<td><select class=\"form-control\" id=\"selschool\"><<option value = \"0\"> --Select Category/Level--</option>";

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query all the categories
     $sql = ("SELECT * FROM schools");
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $schoolName = $row['name'];
// more of the html placing the variables inside
//   . here for concatination you concat with . not + in php;
           $selectString.= "<option value = \"$schoolName\">$schoolName</option>";

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