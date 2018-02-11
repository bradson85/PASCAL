<?php
 require_once("dbconfig.php");

function dbGetStudents(){
   
    
    // start html selelct class
       $selectString = "<td ><select class=\"form-control\" id=\"selcat\"><<option value = \"0\" selected> --Select Student Number--</option>";
    
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query all the categories
         $sql = ("SELECT ID FROM students");
        
         $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $ID = $row['ID'];
    // more of the html placing the variables inside
    //   . here for concatination you concat with . not + in php;
               $selectString.= "<option value = \"$ID\"> $ID </option>";
    
         }
        }
        catch(PDOException $e)
            {
            echo "Error: " . $e->getMessage();
            }
        $pdo = null;
     // close the html brackets
     $selectString.= "</select></td>
     <td><input type='email' id='mailAd' name='email'></td>
     <td><input type='date' id='exdate' name='expires'></td>
     ";
     echo $selectString; // return string.

}

function dbGetAssessments(){
  
    
    // start html selelct class
       $selectString = "<td class='col-md-4'><select class=\"form-control\" id=\"selcat\"><<option value = \"0\" selected> --Select Assessment--</option>";
    
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query all the categories
         $sql = ("SELECT categories.name, categories.level FROM categories INNER JOIN assessments ON categories.ID = assessments.catID");
        
         $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $catName = $row['name'];
               $catLevel = $row['level'];
    // more of the html placing the variables inside
    //   . here for concatination you concat with . not + in php;
               $selectString.= "<option value = \"$catName $catLevel\"> $catName - Level $catLevel </option>";
    
         }
       
        }
        catch(PDOException $e)
            {
            echo "Error: " . $e->getMessage();
            }
        $pdo = null;
     // close the html brackets
        $selectString.= "</select></td>";
        echo $selectString; // return string.
    
}

function dbGetMoreInfo(){
        
        
    }

    function dbViewRecentAssessments(){
      
        
        // start html selelct class
           $selectString = "";
        
            try {
                $pdo = new PDO(DB_CONNECTION_STRING,
                DB_USER, DB_PWD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
            // query all the categories
             $sql = ("SELECT assignedAssessments.ID, assignedAssessments.studentID, assignedAssessments.Expiration, assessments.ID AS 'assessID' 
                    FROM assignedAssessments INNER JOIN assessments ON assignedAssessments.assessmentID = assessments.ID ");
            
             $result = $pdo->query($sql);
             while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                   $ID = $row['ID'];
                   $studentID= $row['studentID'];
                   $assessmentID= $row['assessID'];
                   $expiration= $row['Expiration'];
                   $category =getCategoryAndLevel($assessmentID);
                   $date =date_create($expiration);
                   $dateout = date_format($date,"d/m/Y");
        // more of the html placing the variables inside
        //   . here for concatination you concat with . not + in php;
                   $selectString.= "<tr>
                  <td>$category </td>
                    <td>$studentID</td>
                   <td>$dateout</td></tr>";
        
             }
            }
            catch(PDOException $e)
                {
                echo "Error: " . $e->getMessage();
                }
            $pdo = null;
         // close the html brackets
            echo $selectString; // return string.
        
    }

    function dbViewExpiredAssessments(){
        
        
    }

    function getCategoryAndLevel($assessmentID){
       
        
        // start html selelct class
           $selectString = "";
        
            try {
                $pdo = new PDO(DB_CONNECTION_STRING,
                DB_USER, DB_PWD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
            // query all the categories
             $sql = ("SELECT categories.name, categories.level FROM categories INNER JOIN assessments ON categories.ID = $assessmentID");
            
             $result = $pdo->query($sql);
             while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                   $name = $row['name'];
                   $level= $row['level'];
                
        // more of the html placing the variables inside
        //   . here for concatination you concat with . not + in php;
                   $selectString.= "$name Level $level";
        
             }
            }
            catch(PDOException $e)
                {
                echo "Error: " . $e->getMessage();
                }
            $pdo = null;
         // close the html brackets
           return $selectString;
        

    }


?>