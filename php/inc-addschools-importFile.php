<?php
// This file imports CSV file into database
require_once("../dbconfig.php");
if(isset($_FILES["InputFile"]["name"])) // check to see if file is being uploaded
{
   
    $filename= $_FILES["InputFile"]["name"];
    $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));
     
    //we check,file must be have csv extention
    if($ext==".csv")
    {
      $file = fopen($_FILES["InputFile"]["tmp_name"], "r"); // get it from temp folder

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) // retrive data from csv
        {
           // query to see if data exists
     $sql = $pdo->prepare("SELECT name From schools WHERE name = :name");
     $sql->bindParam(':name', $name);
     
        $name = trim($data[0]," ");;
         $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
           if(!$row) { // no data exists then insert
    
           
            $sql = $pdo->prepare("INSERT INTO schools (name)
             Values (:name)");
            $sql->bindParam(':name', $name);
            $name = trim($data[0]," ");
           $sql->execute();
          
                
           }
            else{ // if data exists then update
               
               $sql = $pdo->prepare("UPDATE schools SET name = :name WHERE name = :name");

                $sql->bindParam(':name', $name);
              
               $name = trim($data[0]," ");
               $sql->execute();
    
               
                
            }
     }
    }
    catch(PDOException $e)
        {
            // use "fal" in get for fail 
        $error = "Error: " . $e->getMessage();
        header("Location: ../add_editschools.php?fal=$error");
        }
    $pdo = null;

        fclose($file); // close file
         // this redirects back to add words page with a message in the get
         // use "imp" in get for fail
        $success = "CSV import success" ;
        header("Location: ../add_editschools.php?imp=$success");
    }
    else {
         // this redirects back to add words page with a message in the get
         // use "fal" in get for fail 
        $error=  "Error: No csv found";
        header("Location: ../add_editschools.php?fal=$error");
    }
    }
    ?>






