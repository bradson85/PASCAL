<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");


if(isset($_POST['classSelect'])){
    $option = $_POST['classSelect']; 
         echo assembleClassSelect($option);
}

if(isset($_POST['classChoice'])){
    $class = $_POST['classChoice']; 
         echo assembleInfoTable($class);
}

if(isset($_POST['assessment'])){
    $assessment = $_POST['assessment']; // for now nothing
         echo assembleAssessmentTable();
}

if(isset($_POST['numComplete'])){
   // $assessment = $_POST['numComplete']; // for now nothing
         echo assessmentsFinished();
}

if(isset($_POST['numAvailable'])){
    // $assessment = $_POST['numComplete']; // for now nothing
          echo getAvailableAssessments();
 }

 if(isset($_POST['listChoice'])){
    $class = $_POST['listChoice']; 
         echo assembleClassListTable($class);
}

 

// creat new pd object
function newPDO(){
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

    return $pdo;

}
// preapared statement for insertins updates deletions.
// input: pdo object  output: sql 
function pdo_preparedStmt($pdo,$query,$parmList,$parmData){
$sql = $pdo->prepare($query);
for ($i=0; $i < count($parmList); $i++) { 
    $sql->bindParam($parmList[$i], $parmData[$i]);
}

return $sql->execute();
}

// for select queryies
// Takes PDO object and sql query and returns the resuling data not prepared
function pdo_query($pdo, $query)
{
    $result = $pdo->query($query);
    
    return $result;
}

function pdo_error($message){

    $error =  $message->getCode();
    if($error == 23000){
      return "Cannot delete Category. Terms Exist in the database 
      that depend on this category. Delete all the Terms associated with
      this category first. ";
  
    }else {return "Error". $message;}
      }

// returns all the classIDs a teacher belongs to.
function getTeacherClassIDs(){
   return $_SESSION['class'];
}

function getSchoolID(){
    return $_SESSION['school'];
}

function getSchoolName($schoolID){

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT name FROM schools Where ID = '$schoolID'";
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
            $name = $row["name"];   
            
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
    return $name;

}
     
function studentListTable($classID){
    try {
        $pdo = newPDO();
        $query = ("SELECT accountID FROM classlist
                     WHERE classID ='$classID'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);

          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;

}

function getAssessmentID($studentID,$classID){
    try {
        $pdo = newPDO();
        $query = ("SELECT assessmentID FROM classlist WHERE studentID =
             (SELECT accountID FROM classlist WHERE classID = '$classID' )");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row;
}

function getAssessmentCategoryName($assessmentID){
    try {
        $pdo = newPDO();
        $query = ("SELECT name FROM categories WHERE ID =
             (SELECT catID FROM assessments WHERE ID = '$assessmentID')");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $catName = $row['name'];
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $catName;
}

function getStudentName($accountID){

    try {
        $pdo = newPDO();
        $query = ("SELECT name FROM accounts WHERE ID = '$accountID'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $catName = $row['name'];
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $catName;

} 

function getClassInfoFromSchool($schoolID){
    try {
        $pdo = newPDO();
        $query = ("SELECT name, gradeLevel FROM classes WHERE schoolID = \"$schoolID\"");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row;
}


function getClassInfo($classID){
    try {
        $pdo = newPDO();
        $query = ("SELECT name, gradeLevel FROM classes WHERE ID = \"$classID\"");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
    }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row;

}

function getCategoryLevelFromCatID($catID){

    try {
        $pdo = newPDO();
        $query = ("SELECT level FROM categories WHERE ID = '$catID'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $catLev = $row['level'];
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $catLev;


}

function getCategoryNameFromCatID($catID){
    try {
        $pdo = newPDO();
        $query = ("SELECT name FROM categories WHERE ID = '$catID'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $catName = $row['name'];
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $catName;
}


function getAsessmentData(){
    try {
        $pdo = newPDO();
        $query = ("SELECT ID, start_date, end_date, catID FROM assessments");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
    }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row;

}


function getClassName($classID){
    $info = getClassInfo($classID);
   return $info[0]['name'];
}

function getClassGradeLevel($classID){
    $info = getClassInfo($classID);
   return $info[0]['gradeLevel'];

}

function checkForTeacher($ID){

    $exists = false;
    try {
        $pdo = newPDO();
        $query = ("SELECT name FROM accounts WHERE ID = '$ID' AND type =1");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row){
            $exists = true;
        }
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $exists;

} 

function getAvailableAssessments(){
    try {
        $pdo = newPDO();
        $query = ("SELECT Count(*) as total
        FROM (SELECT DISTINCT ID FROM assessments) AS T");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $count = $row['total'];
    }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $count;

}



function assessmentsFinished(){
    try {
        $pdo = newPDO();
        $query = ("SELECT Count(*) as total
        FROM (SELECT DISTINCT assessmentID FROM results) AS T");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $count = $row['total'];
    }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $count;

}


 // Name, Grade ,Class ,Assessment Name ,assess level, Date Completed
function assembleInfoTable($classID){
  $studentList = studentListTable($classID);  // all the acount ID's from of student in this calss
  $string="";
    foreach ($studentList as $value) {
        if (!checkForTeacher($value["accountID"])){
        $studentName = getStudentName($value["accountID"]);
        $className = getClassName($classID);
        $gradeLevel = getClassGradeLevel($classID);
        
  $string .= "<tr><td>$studentName</td>
   <td>$gradeLevel</td>
   <td>$className</td> 
         <td> Assessment 38 </td>  
          <td> 4</td>
           <td>2/1/18</td></tr>" ; // html stuff
        }
    }
    unset($value); //php manual says do this after for each

    return $string;
}
 //ID , Start Date End Date Category and Grade Level
function assembleAssessmentTable(){
   $assessmentList = getAsessmentData();
   $string="";
   foreach ($assessmentList as $value) {
    $catName = getCategoryNameFromCatID($value['catID']);
    $catLev = getCategoryLevelFromCatID($value['catID']);
    $tempdate = $value['start_date'];
    $timestamp = strtotime($tempdate);
    $startdate = date("m-d-Y", $timestamp);
    $tempdate = $value['end_date'];
    $timestamp = strtotime($tempdate);
    $enddate = date("m-d-Y", $timestamp);
    $id = $value["ID"];
    
$string .= "<tr><td>$id</td>
<td>$startdate</td>
<td>$enddate</td> 
<td>$catName - $catLev</td></tr>" ; // html stuff
    }

unset($value); //php manual says do this after for each

return $string;
}

function assembleClassSelect($option){

$teacherClassesID = getTeacherClassIDs();

$selectString = "<td><select class='form-control' id='$option'><option disabled selected value = \"0\"> Select A Class</option>";

foreach ($teacherClassesID as $value) {
    $classname = getClassName($value["classID"]);
    $selectString.= "<option value = \"".$value["classID"]."\"> $classname</option>";
}
unset($value); //php manuel says do this after for each

$selectString.= "</select></td>";


return $selectString;

}



function assembleClassListTable($classID){
    $studentList = studentListTable($classID);  // all the acount ID's from of student in this calss
    $string="";
      foreach ($studentList as $value) {
          if (!checkForTeacher($value["accountID"])){
          $studentName = getStudentName($value["accountID"]);
          $className = getClassName($classID);
          $gradeLevel = getClassGradeLevel($classID);
          $schoolID =   getSchoolID();
          $schoolName = getSchoolName($schoolID);

    $string .= "<tr><td>$studentName</td>
     <td>$gradeLevel</td>
     <td>$className</td> 
           <td> $schoolName </td></tr>" ; // html stuff
          }
      }
      unset($value); //php manual says do this after for each
  
      return $string;
  }



?>