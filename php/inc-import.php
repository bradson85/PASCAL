<?php
// This file imports CSV file into database
require_once("../dbconfig.php");
include "inc-addwords-functions.php";

if(isset($_FILES["InputFile"]["name"])) // check to see if file is being uploaded
{
   
    $filename= $_FILES["InputFile"]["name"];
    $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));
     
    //we check,file must be have csv extention
    if($ext==".csv")
    {
      $file = fopen($_FILES["InputFile"]["tmp_name"], "r"); // get it from temp folder
     $flag = true;
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) // retrive data from csv
        {
            if($flag){
                $flag = false;
            }else {
           // query to see if  category data exists
     $sql = $pdo->prepare("SELECT name, level
                           FROM categories 
                            WHERE name = :category AND level = :gradeLevel");
     $sql->bindParam(':gradeLevel', $gradeLevel);
     $sql->bindParam(':category', $category);
     $gradeLevel = trim($data[3]," ");
     $category = trim($data[4]," ");
         $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
           if(!$row) { // no data exists then insert
    
           
            $sql = $pdo->prepare("INSERT INTO categories (name, gradeLevel)
             Values (:name, :gradeLevel)");
            $sql->bindParam(':name', $name);
            $sql->bindParam(':gradeLevel', $gradeLevel);
            $name = trim($data[4]," ");
            $gradeLevel = trim($data[3]," ");
           $sql->execute();
          
                
           }
            else{ // if data exists then do nothing
                   
            }


                    // query to see if data exists
     $sql = $pdo->prepare("SELECT definition FROM terms 
      WHERE definition = :def");
    $sql->bindParam(':def', $def);
$def= trim($data[1]," ");
$sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC) ;
if(!$row) { // no data exists then insert

$sql = $pdo->prepare("INSERT INTO terms (name, definition, catID)
Values (:name, :def, :catID)");
$sql->bindParam(':name', $name);
$sql->bindParam(':def', $def);
$sql->bindParam(':catID', $catID);
$name = trim($data[0]," ");
$def = trim($data[1]," ");
$def = trim(matchCatID(trim($data[4]," "),trim($data[3]," ")),"");
$sql->execute();


}
else{ // if data exists then do nothing
    echo "here";
}
     }
    }
    }
    catch(PDOException $e)
        {
            // use "fal" in get for fail 
        $error = "Error: " . $e->getMessage();
        header("Location: ../import.php?fal=$error");
        }
    $pdo = null;

        fclose($file); // close file
         // this redirects back to add words page with a message in the get
         // use "imp" in get for fail
        $success = "CSV import success" ;
        header("Location: ../import.php?imp=$success");
    }
    else {
         // this redirects back to add words page with a message in the get
         // use "fal" in get for fail 
        $error=  "Error: No csv found";
        header("Location: ../import.php?fal=$error");
    }
    }
    ?>







