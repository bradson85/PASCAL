<?php
require_once("../db/dbconfig.php");

    if(isset($_POST['data']))
        GetCategories("categorySelect", 0);

    function GetCategories($val, $setVal)
    {
        $selectString = "<select id=\"$val\" class=\"styled-select slate\">";

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
           if($count < 1)
           {
            if($setVal == 0)
                $selectString .=  "<option value =\"0\" selected>--Select Category/Level--</option>";
             else
                $selectString .= "<option value =\"0\">--Select Category/Level--</option>";
           }
           
            if($catID == $setVal)
                $selectString .="<option value=\"$catID\" selected> $catName - Level $level</option>";
            else
                $selectString.= "<option value = \"$catID\"> $catName - Level $level</option>";
            $count++;
      }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 
    $selectString.= "</select>";
    if($val == "categorySelect")
        echo $selectString;
    else
        return $selectString;
    }
?>