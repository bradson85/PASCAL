<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");

if(isset($_POST['classSelect'])){
    $option = $_POST['classSelect']; 
         echo assembleClassSelect($option);
}

if(isset($_POST['schoolSelect'])){ 
         echo assembleSchoolSelect();  
}

if(isset($_POST['graphSchoolSelect'])){ 
    echo assembleGraphSchoolSelect();  
}


if(isset($_POST['graphClassChoice'])){ 
    $class = $_POST['graphClassChoice'];
    echo assembleStudentSelect($class) ;
}

if(isset($_POST['graphTeahClass'])){ 
    $school = getSchoolID();
         echo assembleGraphClassSelect($school);
}

if(isset($_POST['classChoice'])){
    $class = $_POST['classChoice']; 
         echo assembleInfoTable($class);
}

if(isset($_POST['schoolChoice'])){
    $school = $_POST['schoolChoice']; 
         echo assembleAdminClassSelect($school);
}

if(isset($_POST['graphSchoolChoice'])){
    $school = $_POST['graphSchoolChoice']; 
         echo assembleGraphClassSelect($school);
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

 // for class list page 
 if(isset($_POST['listChoice'])){
    $class = $_POST['listChoice']; 
         echo assembleClassListTable($class);
}

if(isset($_POST['categorySelect'])){
     
         echo createCatAndLevelSelect("ID") ;
}


if(isset($_POST['name'])){
    $name = $_POST['name']; 
    $email = $_POST['email'];
    $classID = $_POST['classID'];
    $accountID = getAccountID($name,$email);
    $results = checkResults($accountID); 
    if($results){
       echo deleteFromTable($accountID,$classID);
    } else echo "Assessment results exist. Cannot delete student.";
       
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
      return "Cannot delete. Other data depends on this Data. ";
  
    }else {return "Error". $message;}
      }

      function createCatAndLevelSelect($selected){

        $selectstring= array();
        $selectVal =array();
        $htmlIDName = "selcat";
        $titleOption = "--Select Category/Level--";
        $categories = getCategoriesData("ID");
        $count = 0;
        foreach ($categories as $value) {
            $selectstring[$count]= "".$value["catName"]." - Level ".$value["level"];
            $selectVal[$count]= "".trim($value["catName"]). " ".trim($value["level"]);
            $count = $count+1;
        }
           unset($value);
        return createHTMLSelect($selectstring,$selectVal,$htmlIDName,$titleOption,$selected);
        
    }
    function protectOrderStrings($string){
        $pieces = explode(" ",$string);
        if(str_word_count($string) > 2){
                           return false;           
        } else if (strcmp($pieces[1], "ASC") ==0 || strcmp($pieces[1], "DESC") ==0){
            return true;
        } else return false;


  }
  function createHTMLSelect($optionContent,$optionID,$idName,$selectTitle,$selected){

    $selectString = "<td><select class=\"form-control\" 
    id=\"$idName\"><<option value = \"0\" selected disabled> $selectTitle</option>";
   $count = 0;
    foreach ($optionContent as $value) {
        if(isset($selected) && ((strcasecmp($selected,$optionID[$count])== 0)|| ($selected == $optionID[$count]))){
            $selectString.= "<option value = \"".$optionID[$count]."\" selected>".$value."</option>";
            $count = $count+1;
        }else {
        $selectString.= "<option value = \"".$optionID[$count]."\">".$value."</option>";
         $count = $count+1;
        }
    }
    unset($value);
    $selectString.= "</select></td>";

    return $selectString;

}

    function  getCategoriesData($sortBy){
        if ( !protectOrderStrings($sortBy)){
            $query = ("SELECT name as catName, level FROM categories");
           } else   $query = ("SELECT name as catName, level FROM categories ORDER BY $sortBy");
     try {
           $pdo = newPDO();
           $result = pdo_query($pdo,$query);
            
           $row = $result->fetchAll(PDO::FETCH_ASSOC);
             }
         catch(PDOException $e)
             {
             return pdo_error($e);
             }
         $pdo = null;
    return $row;
    }

// returns all the classIDs a teacher belongs to.
function getTeacherClassIDs(){
   return $_SESSION['class'];
}

function getSchoolID(){
    return $_SESSION['school'];
}
function getSchoolList(){
    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM schools");
        $result = pdo_query($pdo,$query);;
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
         
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;
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



function getStudentInfo($accountID){
    $info = array();
    try {
        $pdo = newPDO();
        $query = ("SELECT name, password, email FROM accounts WHERE ID = '$accountID'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $info = array("name"=>$row['name'],"pwd"=>$row['password'],"email"=> $row["email"]);
        }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $info;

} 

function getClassInfoFromSchool($schoolID){
    try {
        $pdo = newPDO();
        $query = ("SELECT ID, name, gradeLevel FROM classes WHERE schoolID = \"$schoolID\"");
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
        FROM (SELECT DISTINCT assessmentID, studentID FROM results) AS T");
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

function getAccountID($name,$email){
   try {
    $pdo = newPDO();
    $query = ("SELECT ID FROM accounts WHERE name='$name' and email ='$email'");
    $result = pdo_query($pdo,$query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $info = $row['ID']; 
      }  catch(PDOException $e)
         {
          echo pdo_error($e);
         }
      $pdo = null;
      return $info;
}

// delete item from table
function deleteFromTable($accountID,$classID){
    try {
       $pdo = newPDO();
       $query= ("DELETE FROM classlist WHERE accountID = :accountID AND classID = :classID" );
        $list = array(0 => ":accountID",1=>":classID");
        $newdata = array(0 => $accountID,1 => $classID); 
       $success = pdo_preparedStmt($pdo,$query,$list,$newdata);
         if($success){
         echo "Deleted Item Successfully";
         }
         else{ echo "Deletion of Data ID:$data Failed";}
         }
     catch(PDOException $e)
         {
         echo pdo_error($e);
         }
     $pdo = null;

}



 // Name, Grade ,Class ,Assessment Name ,assess level, Date Completed
function assembleInfoTable($classID){
  $studentList = studentListTable($classID);  // all the acount ID's from of student in this calss
  $string="";
    foreach ($studentList as $value) {
        if (!checkForTeacher($value["accountID"])){
        $info = getStudentInfo($value["accountID"]);
        $email= $info['email'];
        $studentName = getStudentName($value["accountID"]);
        $className = getClassName($classID);
        $gradeLevel = getClassGradeLevel($classID);
        
  $string .= "<tr class='studentLink' id='$email'><td><u class='text-success'>$studentName</u></td>
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

 // Name, Grade ,Class ,Assessment Name ,assess level, Date Completed
 function assembleStudentSelect($classID){
    $studentList = studentListTable($classID);  // all the acount ID's from of student in this calss
    $selectString = "<td><select class='form-control' id='studentSelect'><option disabled selected value = \"0\"> Select A Student</option>";

    foreach ($studentList as $value) {
        if (!checkForTeacher($value["accountID"])){
            $info = getStudentInfo($value["accountID"]);
            $email= $info['email'];
            $studentName = getStudentName($value["accountID"]);
            
            $selectString.= "<option value = '$email'> $studentName</option>";
            }
    
    }
    unset($value); //php manuel says do this after for each
    
    $selectString.= "</select></td>";
    
    
    return $selectString;
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
    
$string .= "<tr class='assessmentLink' id='$id'><td>$id</td>
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
// for admin dashboard
function assembleAdminClassSelect($school){

   $schoolinfo = getClassInfoFromSchool($school);
    $selectString = "<td><select class='form-control' id='adminClassList'><option disabled selected value = \"0\"> Select A Class</option>";
    
    foreach ($schoolinfo as $value) {
        $classname = $value["name"];
        $selectString.= "<option value = \"".$value["ID"]."\"> $classname</option>";
    }
    unset($value); //php manuel says do this after for each
    
    $selectString.= "</select></td>";
    
    
    return $selectString;
    
    }
 // graph choices
    function assembleGraphClassSelect($school){

        $schoolinfo = getClassInfoFromSchool($school);
         $selectString = "<td><select class='form-control' id='graphClassList'><option disabled selected value = \"0\"> Select A Class</option>";
         
         foreach ($schoolinfo as $value) {
             $classname = $value["name"];
             $selectString.= "<option value = \"".$value["ID"]."\"> $classname</option>";
         }
         unset($value); //php manuel says do this after for each
         
         $selectString.= "</select></td>";
         
         
         return $selectString;
         
         }

function assembleSchoolSelect(){

    
    $selectString = "<td><select class='form-control' id='schoolSelect'><option disabled selected value = \"0\"> Select A School</option>";
    $school = getSchoolList();
    foreach ($school as $value) {
        $schoolname = $value["name"];
        $selectString.= "<option value = \"".$value["ID"]."\"> $schoolname</option>";
    }
    unset($value); //php manuel says do this after for each
    
    $selectString.= "</select></td>";
    
    
    return $selectString;
    
    }

    function assembleGraphSchoolSelect(){

    
        $selectString = "<td><select class='form-control' id='graphSchoolSelect'><option disabled selected value = \"0\"> Select A School</option>";
        $school = getSchoolList();
        foreach ($school as $value) {
            $schoolname = $value["name"];
            $selectString.= "<option value = \"".$value["ID"]."\"> $schoolname</option>";
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
          $info = getStudentInfo($value["accountID"]);
          if(!empty(trim($info['pwd']," "))){
            $password = "&#10003";
        } else $password = "No Password";
          $studentName = $info['name'];
          $email= $info['email'];
          $className = getClassName($classID);
          $gradeLevel = getClassGradeLevel($classID);
          $schoolID =   getSchoolID();
          $schoolName = getSchoolName($schoolID);

    $string .= "<tr><td>$studentName</td>
    <td>$email</td>
    <td>$password</td>
     <td>$gradeLevel</td>
     <td>$className</td> 
           <td> $schoolName </td>
           <td id='hiddenbutton' hidden> <a class='btn btn-danger btn-sm'  id='deleteChoice' href='#'>Delete Student</a> </td></tr>" ; // html stuff
          }
      }
      unset($value); //php manual says do this after for each
  
      return $string;
  }
//returns true if results don't exist.
  function checkResults($accountID){
      $exists =false;
    try {
     $pdo = newPDO();
     $query = ("SELECT * FROM results WHERE studentID='$accountID'");
     $result = pdo_query($pdo,$query);
     $row = $result->fetch(PDO::FETCH_ASSOC);
    if(!$row){
            $exists =true;
    }
       }  catch(PDOException $e)
          {
           echo pdo_error($e);
          }
       $pdo = null;
       return $exists;
 }
 

?>