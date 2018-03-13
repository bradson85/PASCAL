<?php 
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
if(isset($_POST['studentData'])){
    $email = $_SESSION['currStudentEmail'];
$allAssessments = getAllAssessments();
$arr['name'] = getName($email);
$arr[0] = 0;
foreach ($allAssessments as $value) {
    if(checkIfResultsExist($email,$value['ID'])){
   $arr[$value['ID']] = getStudentTestData($email,$value['ID']);
    }
}
unset($value);
    echo json_encode($arr);
}else if(isset($_POST['type'])){
    if(strcmp($_POST['type'],"student")==0){
    $email = $_POST['choice'];
    $allAssessments = getAllAssessments();
    $arr['name'] = getName($email);
    $arr[0] = 0;
foreach ($allAssessments as $value) {
    if(checkIfResultsExist($email,$value['ID'])){
   $arr[$value['ID']] = getStudentTestData($email,$value['ID']);
    }
}
    } else if(strcmp($_POST['type'],"class")==0){
        $classID = $_POST['choice'];
       $classList = getClassList($classID);
       $arr = getClassStudentScores($classList);
       $arr['name'] = "Average of " .getClassName($classID);
    }else  

unset($value);
    echo json_encode($arr);
}
else echo "No student selected";

function checkIfResultsExist($email,$assessID){
    $exists = false;
try {
    $pdo = newPDO();
    $query = ("SELECT count(r.ID) FROM results AS r, accounts AS s WHERE assessmentID = $assessID AND s.email = '$email' AND s.ID = r.studentID");
    $result = pdo_query($pdo,$query);
    $row = $result->fetch();
    if($row[0] > 0){
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

function getName($email){
    try {
        $pdo = newPDO();
        $query = ("SELECT name From accounts  WHERE email = '$email'");
        $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
    } 
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row['name'];

}

function getClassName($classID){
    try {
        $pdo = newPDO();
        $query = ("SELECT name From classes WHERE ID = '$classID'");
        $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
    } 
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row['name'];

}

function getStudentTestData($email,$assessID){
    try {
        $pdo = newPDO();
        $query = ("SELECT count(r.ID) FROM results AS r, accounts AS s WHERE assessmentID = $assessID AND r.correct = 1 AND s.email = '$email' AND s.ID = r.studentID");
        $result = pdo_query($pdo,$query);
        $row = $result->fetch();
    }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $row[0];

}

function getAllAssessments(){
    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM assessments");
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

function getClassList($classID){

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




function getClassStudentScores($classList){  // class list can have multipvle values
    $allAssessments = getAllAssessments();
 foreach ($classList as $value) {
     $arr1 =array();
    $info= getStudentInfo($value['accountID']);
    $email = $info['email'];
    $name  = $info['name'];
    foreach ($allAssessments as $item) {
        if(checkIfResultsExist($email,$item['ID'])){
       $arr1[] = getStudentTestData($email,$item['ID']);
        }
    }
    unset($item);
    if(!checkForTeacher($value['accountID'])){
        $avgScore = getAverage($arr1);
        if(is_nan($avgScore)){
            $arr[$name]= 0;
        } else $arr[$name] = $avgScore;
    }
   
}
unset($value);
return($arr);
}




function getAverage($arr){

    $a = array_filter($arr);
$average = array_sum($a)/count($a);
return $average;
}

function checkForTeacher($ID){

    $exists = false;
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        $sql = ("SELECT name FROM accounts WHERE ID = '$ID' AND type =1");
        $result = $pdo->query($sql);
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



?>