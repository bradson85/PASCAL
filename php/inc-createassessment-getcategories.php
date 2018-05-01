<?php
require_once("../db/dbconfig.php");

    $selectString = "<select id=\"categorySelect\" class=\"styled-select slate\"><option value =\"0\">--Select Category/Level--</option>";

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
     $sql = ("SELECT * FROM categories");
     $count = 0;
     $result = $pdo->query($sql);

     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $catID = $row['ID'];
           $catName = $row['name'];
           $level = $row['level']; 
            
           $selectString.= "<option value = \"$catID\"> $catName - Level $level</option>";
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