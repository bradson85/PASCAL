<?php
require_once("../dbconfig.php");
require("inc-createassessment-getTerms.php");
   
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
     $sql = ("SELECT termID FROM assesmentQuestions WHERE assessmentID = $data");
    
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $catID = $row['termID'];
          
            echo getTerms($catID);
        

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
 
    

?>