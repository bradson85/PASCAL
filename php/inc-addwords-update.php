<?php
// this file updates the database to match the table recored on the webpage
require_once("../dbconfig.php");
include "inc-addwords-functions.php";

$success = 1; // used to detect if a category is not selected from dropdown menu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field and convert jason to array
    $TableData = json_decode(stripslashes($_POST['data']),true);
}
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    for ($i=0; $i < count($TableData); $i++) {
        //query to select everything from database and see if current id exists in the table.
 $sql = $pdo->prepare("SELECT * From terms WHERE ID = :ID");
 $sql->bindParam(':ID', $ID,PDO::PARAM_INT);
  
    $ID = $TableData[$i]['ID']; // id from array
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
       if(!$row) { // if row doesnt exist in the database then insert below
        $splitString = explode(' ',$TableData[$i]['category']); // splits the category and level from tables
        $category =  $splitString[0];                          // category is first of the split string
        $level = $splitString[1];                              // level is second of split screen
      
        if($category == "0"){    // if category isnt selected
           $success= 0;
        }
    else{  //cattegory is selected 
        //insert new rows into table
        $sql = $pdo->prepare("INSERT INTO terms (name, definition, catID)
         Values (:word , :definition, :catID)");
        $sql->bindParam(':word', $word);
       $sql->bindParam(':definition', $def);
       $sql->bindParam(':catID', $catID); 
        $word = trim($TableData[$i]['word']," ");            // Trim To get rid of whitespace at begining and end
        $def = trim($TableData[$i]['definition']," ");      // caused by html.
        $category =  trim($splitString[0]," ");
        $level = trim($splitString[1]," ");
        $catID = trim(matchCatID($category, $level)," ");
       $sql->execute();
         
            }
       }
        else{// when there already exist and item in DB
            //update existing rows.
            $splitString = explode(' ',$TableData[$i]['category']);
            $category =  $splitString[0];
            $level = $splitString[1];

            if($category == "0"){  //category not selected
                $success = 0;
            }
                else{ //category is selected
           $sql = $pdo->prepare("UPDATE terms SET name =:word , definition = :definition, catID = :catID WHERE ID = :IDs");
            $sql->bindParam(':IDs', $ID2,PDO::PARAM_INT);
            $sql->bindParam(':word', $word);
           $sql->bindParam(':definition', $def);
           $sql->bindParam(':catID', $catID); 
           $ID2 = trim($TableData[$i]['ID']," ");
            $word = trim($TableData[$i]['word']," ");        //Trim to clean up leading and trailing whitespace.
            $def = trim($TableData[$i]['definition']," ");
            $category = trim( $splitString[0]," ");
            $level = trim($splitString[1]," ");
            $catID = trim(matchCatID($category, $level)," ");
           $sql->execute();           
            }
        }
 }
}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
       
    echo $success;


?>
