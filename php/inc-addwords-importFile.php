<?php
require_once("../db/dbconfig.php");
include "inc-addwords-functions.php";
echo ($_POST['data']);
if(isset($_FILES["file"]["name"]))
{
   
    echo $filename= $_Files["file"]["name"];
    $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));
     
    //we check,file must be have csv extention
    if($ext=="csv")
    {
      $file = fopen($filename, "r");

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
        {
     $sql = $pdo->prepare("SELECT name, definition From terms WHERE name = :word AND defintion = :definition");
     $sql->bindParam(':word', $word);
     $sql->bindParam(':definition', $def);
        $word = $emapData[0];
        $def = $emapData[1];
         $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
           if(!$row) {
    
           
            $sql = $pdo->prepare("INSERT INTO terms (name, definition, catID)
             Values (:word , :definition, :catID)");
            $sql->bindParam(':word', $word);
           $sql->bindParam(':definition', $def);
           $sql->bindParam(':catID', $catID); 
            $word = $emapData[0];
            $def = $emapData[1];
            $catID = matchCatID($emapData[2], $emapData[3]);
           $sql->execute();
          
                
           }
            else{
               
               $sql = $pdo->prepare("UPDATE terms SET name = :word , definition = :definition, catID = :catID WHERE name = :word AND definition = :definition");
                $sql->bindParam(':IDs', $ID2,PDO::PARAM_INT);
                $sql->bindParam(':word', $word);
               $sql->bindParam(':definition', $def);
               $sql->bindParam(':catID', $catID); 
               $word = $emapData[0];
               $def = $emapData[1];
               $catID = matchCatID($emapData[2], $emapData[3]);
               $sql->execute();
    
               
                
            }
     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
           
        
        fclose($file);
        echo "CSV File has been successfully Imported.";
    }
    else {
        echo "Error: Please Upload only CSV File";
    }
    }
    ?>







