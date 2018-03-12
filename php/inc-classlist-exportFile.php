<?php
session_start();
require_once("../dbconfig.php");
if(isset($_POST['classNum'])){
$classNum = $_POST['classNum'];

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=currentClassList.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// save the column headers
fputcsv($output, array('Name', "Email" ,'Password','Class', 'Grade Level', 'School',"Is Hashed?", "Teacher?"));

$school = getSchoolNameFromClassID($classNum); //name
$classinfo=getClassInfoFromClassID($classNum); // name, gradelevel
$accounts = getClassListAccountID($classNum); /// 2d array of all accounts [accountID]

    foreach ($accounts as $value) {
        $info = getAccountInfo($value["accountID"]);
        if(checkForTeacher($value["accountID"])){
            $arr = Array($info['name'],$info['email'],$info['password'],$classinfo['name'],$classinfo['gradeLevel'],$school,"True", "True");
            fputcsv($output, $arr); // phph function that saves to csv
        }else{
            $arr = Array($info['name'],$info['email'],$info['password'],$classinfo['name'],$classinfo['gradeLevel'],$school,"True", "False");
            fputcsv($output, $arr); // phph function that saves to csv
        }
           
        } 
     fclose($output);
      
    } else echo "Error";   
 

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
    return "Error". $message;}
      
function getClassListAccountID($classNum){
    try {
    $pdo = newPDO();
    $query = "SELECT accountID From classlist Where classID = $classNum";
    $result = pdo_query($pdo,$query);
    $row = $result->fetchAll(PDO::FETCH_ASSOC); // has multile results;
    $pdo = null;
} catch (PDOException $e) {
    echo pdo_error($e);
     }
     return $row; 
}


function getAccountInfo($accountID){
    try {
    $pdo = newPDO();
    $query = "SELECT name , email, password FROM accounts 
    WHERE ID = $accountID";
    $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC) ;
        $pdo = null;
        } catch (PDOException $e) {
            echo pdo_error($e);
             }
             return $row;
}

function getSchoolNameFromClassID($classNum){

    try {
        $pdo = newPDO();
        $query = "SELECT name FROM Schools Where ID = (SELECT schoolID From classes WHERE ID =$classNum)";
        $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC); // has multile results;
        $pdo = null;
    } catch (PDOException $e) {
        echo pdo_error($e);
         }
         return $row['name'];
}

function getClassInfoFromClassID($classNum){

    try {
        $pdo = newPDO();
        $query= "SELECT name, gradeLevel FROM classes WHERE ID = $classNum";
        $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC); // has multile results;
        $pdo = null;
    } catch (PDOException $e) {
        echo pdo_error($e);
         }
         return $row;
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
?>