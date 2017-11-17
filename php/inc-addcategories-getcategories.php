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
     $sql = ("SELECT * FROM categories");
    
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $catID = $row['ID'];
           $catName = $row['name']; 
// more of the html placing the variables inside
//   . here for concatination you concat with . not + in php;
           $selectString.= "<tr><td style='display:none;'>$catID</td><td contenteditable= 'true'>$catName</td>
           <td><select class=\"form-control\" id=\"selLev\"><<option value = \"0\"> --Select Level--</option>
           <option value = \"1\"> 1 </option>
           <option value = \"2\"> 2 </option>
           <option value = \"3\"> 3 </option>
           <option value = \"4\"> 4 </option>
           <option value = \"5\"> 5 </option>
           <option value = \"6\"> 6 </option></select></td>
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