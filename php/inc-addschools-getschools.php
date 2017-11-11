<?php

// this returns all the categories in the database and places them inside of an a select option html dropdown
require_once("../dbconfig.php");

// start html selelct class
   $selectString = "";

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query all the categories
     $sql = ("SELECT * FROM schools");
    
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $ID = $row['ID'];
           $schools = $row['name']; 
// more of the html placing the variables inside
//   . here for concatination you concat with . not + in php;
           $selectString.= "<tr><td>$ID</td><td contenteditable= 'true'>$schools</td>
           <td><button class='btn btn-sm deleteRow'>Delete</button></td></tr> ";

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 // close the html brackets
    echo $selectString; // return string.

?>