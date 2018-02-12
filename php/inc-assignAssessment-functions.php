<?php
 require_once("../dbconfig.php");
 
 if(isset($_POST['init'])){
    $school= dbSelectSchool();
    $class = dbSelectClasses(0); 
    $type  =selectType();
    $students =dbGetStudents(0);
    $assessments =dbGetAssessments();
    print json_encode(array("school"=>$school,"class"=>$class, "type"=>$type,
     "students"=>$students,"assessments"=>$assessments));
 }

 if(isset($_POST['school'])){

    echo dbSelectClasses($_POST['school']);
 }

 if(isset($_POST['class'])){

    echo dbGetStudents($_POST['class']);
 }

 if(isset($_POST['studentChoice'])){

    assignToStudents($_POST['studentChoice'], $_POST['assessmentChoice']);
 }

 //funcitons start here ***********************************

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
     $selectString = "<form><div class='form-group' id ='studentChoice'>
     <label for='sel3'>Select Student:</label>
     <select class='form-control' size='5' id='sel3'><option disabled value = \"0\"> Student Name- Class</option>";
     
 
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
   return $selectString; // return string.

}

function dbGetAssessments(){
   

    $selectString = "
    <label for='sel4'>Select Assessment From list:</label>
    <select class='form-control' id='sel4' size='5' disabled><option selected disabled value = \"0\"> ID - Category - Level</option>";
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
    return $selectString.= "</select>";
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
        <select  class='form-control' size='5' id='sel1'><option disabled value = \"0\"> School Name</option>";
        try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // query all the categories
         $sql = ("SELECT * FROM schools");
             
         $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $id =$row['ID'];
           $school = $row["name"];
           
            $selectString.= "<option value =\"$id \">$school </option></td>"; //html  ;
    
         }
        }
        catch(PDOException $e)
            {
            echo "Error: " . $e->getMessage();
            }
        $pdo = null;
        return $selectString.= "</select></form><br>";

    }

    function dbSelectClasses($schoolID){

        $selectString = "<form><div class='form-group' id ='classNames'>
        <label for='sel2'>Select Class:</label>
        <select class='form-control' size='5' id='sel2'><option disabled value = \"0\"> Class</option>";
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
        return $selectString.= "</select></form><br>";
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

     return   ('<div class="container border" id= "selType">
        <div class="row">
        <div class="col-sm align-self-center">
         <H6>Select Who To Assign To:</H6>
    </div>
    <div class="col-sm align-self-center">
        <form >
  <div class="form-group">
        <div class="form-check form-check-inline">
  <input class="form-check-input"  id = "selStudents" type="radio" name="inlineRadioOptions" value="option1" disabled>
  <label class="form-check-label" for="selStudents">Individual Students</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input"  id = "selClass" type="radio" name="inlineRadioOptions"  value="option2" disabled>
  <label class="form-check-label" for="selClass">Enitre Class</label>
</div>
</div>
</form>
</div>
</div>
</div> <br>');

    }

function assignToStudents($studentID, $assessmentID){
if(checkIfAssignedExists($studentID, $assessmentID)){
  echo "Cannot Assign Another Assessment <br> Student $studentID Is Already Assigned Assessment";
}else {
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare("INSERT INTO assessmentassignments (assessmentID, studentID) VALUES (:assessmentID,:studentID)");
         $sql->bindParam(':studentID', $studentID);
         $sql->bindParam(':assessmentID', $assessmentID);
         $sql->execute();
    }
    catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
    echo 1;
}
}

function checkIfAssignedExists($studentID, $assessmentID){
 $exists = false;
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare("SELECT * FROM assessmentassignments 
                            WHERE studentID = :studentID AND assessmentID = :assessmentID");
         $sql->bindParam(':studentID', $studentID,PDO::PARAM_INT);
         $sql->bindParam(':assessmentID', $assessmentID,PDO::PARAM_INT);
         $sql->execute();
         while(  $row = $sql->fetch(PDO::FETCH_ASSOC)){
            if($row) { // if row exist in the database then return true;
                $exists = true;
            }
         }
    }
    catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
    return $exists;

}

function assignToClass($classID,$assessmentID){

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        $sql = $pdo->prepare("SELECT accountID FROM classlist 
                            WHERE classID = :classID ");
         $sql->bindParam(':classID', $classID,PDO::PARAM_INT);
         $sql->execute();
         while(  $row = $sql->fetch(PDO::FETCH_ASSOC)){
             $studentID = $row['accountID'];

            assignToStudents($studentID,$assessmentID);
           
         }
    }
    catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
    
}


?>