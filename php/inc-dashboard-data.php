<?php
session_start();
require_once("../dbconfig.php");


if(isset($_POST['categoriesSel'])){

   
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

 // funciton to get all studetns in a lcass
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

// funciton to get all studetns in a lcass
function getClassList($id){

    try {
        $pdo = newPDO();
        $query = ("SELECT accountID FROM classlist
        WHERE classID = \"$id\"");
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



//return a select box with a list of all the classess.
function getClassData(){

    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM classes");
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


 // this creates and html select item
// used alot in the tables we create
// takes one to many options data, the name that the id html attribute needs
// and the first default tilte ie category, or level etc
function createHTMLSelect($optionContent,$optionID,$idName,$selectTitle){

    $selectString = "<td><select class=\"form-control\" 
    id=\"$idName\"><<option value = \"0\"> $selectTitle</option>";
   $count = 0;
    foreach ($optionContent as $value) {
        $selectString.= "<option value = \"".$optionID[$count]."\">".$value."</option>";
         $count = $count+1;
    }
    unset($value);
    $selectString.= "</select></td>";

    return $selectString;

}

function getSearchBoxSchool(){

$selectString = "<td><select class=\"form-control\" id=\"sort\"><<option value=\"0\">Select School</option>";

try {
    $pdo = newPDO();
// query all the categories
$query = ("SELECT * FROM schools");

$result = pdo_query($pdo,$query);
 while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
       $schoolName = $row['name'];
// more of the html placing the variables inside
//   . here for concatination you concat with . not + in php;
       $selectString.= "<option value = \"$schoolName \"> $schoolName</option>";
 }
}
catch(PDOException $e)
    {
        echo pdo_error($e);
    }
$pdo = null;
// close the html brackets
$selectString.= "</select></td>";
echo $selectString; // return string.
}


function getSearchBoxClass(){

    $selectString = "<td><select class=\"form-control\" id=\"sort\"><<option value=\"0\">Select Class</option>";
    
    try {
        $pdo = newPDO();
    // query all the categories
    $query = ("SELECT * FROM classes");
    
    $result = pdo_query($pdo,$query);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $className = $row['name'];
           $gradeLevel = $row['gradeLevel'];
    // more of the html placing the variables inside
    //   . here for concatination you concat with . not + in php;
           $selectString.= "<option value = \"$schoolName \"> $schoolName</option>";
     }
    }
    catch(PDOException $e)
        {
            echo pdo_error($e);
        }
    $pdo = null;
    // close the html brackets
    $selectString.= "</select></td>";
    echo $selectString; // return string.
    }

?>