<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");


if(isset($_POST['option'])){
    $option = $_POST['option']; // for now nothing
         echo assembleClassSelect();
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
     
function studentListTable($classID){
    try {
        $pdo = newPDO();
        $query = ("SELECT accountID FROM classlist WHERE classID = '$classID'");
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
        $query = ("SELECT name FROM accounts WHERE ID = '$accountID' AND type = 2");
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


function getClassName($classID){
    $info = getClassInfo($classID);
   return $info[0]['name'];
}

function getClassGradeLevel($classID){
    $info = getClassInfo($classID);
   return $info[0]['gradeLevel'];

}


 // Name, Grade ,Class ,Assessment Name ,Score, Date Completed
function assembleInfoTable($classID){
  $studentList = studentListTable($classID);  // all the acount ID's from of student in this calss
    foreach ($studentList as $value) {
        $studentName = getStudentName($value["accountID"]);
        $className = getClassName($classID);
        $gradeLevel = getClassGradeLevel($classID);
        
   "<tr><td'>$studentName</td><td>
           <select class='form-control' id='selcat'><<option value = \"0\"> --Select Category/Level--</option> $input
          </select></td> 
         <td contenteditable= 'true'> $word </td>  
          <td contenteditable= 'true'>$def</td>
           <td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>" ; // html stuff
    }
    unset($value); //php manual says do this after for each
}

function assembleClassSelect(){

$teacherClassesID = getTeacherClassIDs();

$selectString = "<td><select class='form-control' id='sel3'><option disabled selected value = \"0\"> Class Choice</option>";

foreach ($teacherClassesID as $value) {
    $classname = getClassName($value["classID"]);
    $selectString.= "<option value = \"".$value["classID"]."\"> $classname</option>";
}
unset($value); //php manuel says do this after for each

$selectString.= "</select></td>";


return $selectString;

}



?>