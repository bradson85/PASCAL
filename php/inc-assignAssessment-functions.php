<?php
 require_once("dbconfig.php");


 

function dbGetStudentNames($id){
  
   

        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query all the categories
         $sql = ("SELECT name FROM accounts WHERE type = '2' And ID =$id");
         $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $name = $row['name'];
               
    
         }
        }
        catch(PDOException $e)
            {
            echo "Error: " . $e->getMessage();
            }
        $pdo = null;
     
    return  $name;

}

function dbGetStudents($id){

     // start html selelct class
     $selectString = "<form><div class='form-group'>
     <label for='sel3'>Select Student:</label>
     <select multiple class='form-control' id='sel3'><option disabled value = \"0\"> Student Name- Class</option>";
     
 
   try {
       $pdo = new PDO(DB_CONNECTION_STRING,
       DB_USER, DB_PWD);
       $pdo->setAttribute(PDO::ATTR_ERRMODE,
       PDO::ERRMODE_EXCEPTION);
   // query all the categories
    $sql = ("SELECT accountID FROM classlist WHERE classID =$id");
    $result = $pdo->query($sql);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $accountID = $row['accountID'];
           $name = dbGetStudentNames($accountID);

          // more of the html placing the variables inside
    //   . here for concatination you concat with . not + in php;
    $selectString.= "<option value = \"$accountID\"> $name</option>"; //html  ;
    }
   }
   catch(PDOException $e)
       {
       echo "Error: " . $e->getMessage();
       }
   $pdo = null;
   $selectString.= "</select></form><br>";
   echo $selectString; // return string.

}

function dbGetAssessments(){
   

    $selectString = "
    <label for='sel4'>Select Assessment From list:</label>
    <select class='form-control' id='sel4' disabled><option selected disabled value = \"0\"> ID - Category - Level</option>";
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query all the categories
     $sql = ("SELECT * FROM assessments");
         
     $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
         $id = $row["ID"];  
        $category = getCategoryAndLevel($row['catID']);
       
        $selectString.= "<option value =\"$id\">$id - $category </option></td>"; //html  ;

     }
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
    echo $selectString.= "</select>";
}


    function getCategoryAndLevel($ID){
       
        
        // start html selelct class
           $selectString = "";
        
            try {
                $pdo = new PDO(DB_CONNECTION_STRING,
                DB_USER, DB_PWD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
            // query all the categories
             $sql = ("SELECT name, level FROM categories WHERE ID = $ID");
            
             $result = $pdo->query($sql);
             while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                   $name = $row['name'];
                   $level= $row['level'];
                
        // more of the html placing the variables inside
        //   . here for concatination you concat with . not + in php;
                   $selectString.= "$name - Level $level";
        
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

    function dbSelectSchool(){
        $selectString = "<form><div class='form-group' id='schoolChoice'>
        <label for='sel1'>Select School:</label>
        <select multiple class='form-control' id='sel1'><option disabled value = \"0\"> School Name</option>";
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query all the categories
         $sql = ("SELECT name FROM schools");
             
         $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            
           $school = $row["name"];
           
            $selectString.= "<option value =\"$school \">$school </option></td>"; //html  ;
    
         }
        }
        catch(PDOException $e)
            {
            echo "Error: " . $e->getMessage();
            }
        $pdo = null;
        echo $selectString.= "</select></form><br>";

    }

    function dbSelectClasses($schoolID){

        $selectString = "<form><div class='form-group '>
        <label for='sel2'>Select Class:</label>
        <select multiple class='form-control' id='sel2'><option disabled value = \"0\"> Class</option>";
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query all the categories
         $sql = ("SELECT * FROM classes Where schoolID = $schoolID ");
             
         $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
             $id = $row['ID'];  
           $class = $row["name"];
           
            $selectString.= "<option value =\"$id\">$class </option></td>"; //html  ;
    
         }
        }
        catch(PDOException $e)
            {
            echo "Error: " . $e->getMessage();
            }
        $pdo = null;
        echo $selectString.= "</select></form><br>";
    }

    

    function assignedAssessments($id){
    
        $assessmentID = 0;
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query to get all categries for drop down menu
            $sql = "SELECT assessmentID FROM assessmentassignments Where studentID = '$id'";
            $result = $pdo->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
                $assessmentID = $row["assessmentID"];   
                
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
        return $assessmentID;
    }
    
    function getAssessmentData($id){
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query to get all categries for drop down menu
            $sql = "SELECT * FROM assessments where ID = '$id'";
            $result = $pdo->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
                $start = $row["assessmentID"];   
                $end = $row["assessmentID"];
                $class = getClassName($row['classID']);
                $category = getCategoryName($row['catID']);
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
    
          $arr = array( "category" =>$category,
          "class" => $class,
          "end" => $end,
          "start" => $start
          );
           
          return $arr;
    
    }
    
    
    function getClassName($id){
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query to get all classes 
            $sql = "SELECT name FROM classes where ID = '$id'";
            $result = $pdo->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
                $classname = $row["name"];   
                    
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
     return $classname;
    }
    
    function getCategoryName($id){
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query 
            $sql = "SELECT name, level  FROM categories where ID = '$id'";
            $result = $pdo->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
               // $level = $row["level"];   
                $name = $row['name'];    
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
        return $name;
    
    }


    function selectType(){

     echo   ('<br><div class="container border">
        <div class="row">
        <div class="col-sm align-self-center">
         <H6>Select Who To Assign To:</H6>
    </div>
    <div class="col-sm align-self-center">
        <form>
  <div class="form-group">
        <div class="form-check form-check-inline">
  <input class="form-check-input"  id = "selStudents" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" disabled>
  <label class="form-check-label" for="inlineRadio1">Entire Class</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input"  id = "selClass" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" disabled>
  <label class="form-check-label" for="inlineRadio2">Individual Student</label>
</div>
</div>
</form>
</div>
</div>
</div> <br>');

    }


?>