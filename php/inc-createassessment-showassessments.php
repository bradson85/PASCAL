<?php
require_once("../db/dbconfig.php");

   $selectString = "<div id=\"sidemenu\">";

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
     $sql = ("SELECT * FROM categories");
    
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $catID = $row['ID'];
           $catName = $row['name'];
           $level = $row['level']; 

           $selectString.= "<option value = \"$catName $level\"> $catName - Level $level</option>";

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 
    $selectString.= "</select></td>";
    echo $selectString;

?>