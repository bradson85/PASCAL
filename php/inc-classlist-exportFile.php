<?php
session_start();
require_once("../dbconfig.php");

$classNum = $_POST['classNum'];

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=currentClassList.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// save the column headers
fputcsv($output, array('Name', "Email" ,'Password','Class', 'Grade Level', 'School',"Is Hashed?"));

// run query that exports in the format   .
 try {
    $pdo = newPDO();
    $query1 = "SELECT name FROM Schools Where ID = (SELECT schoolID From classes WHERE ID =$classNum)";
    $query3 = "SELECT name, gradeLevel FROM classes WHERE ID = $classNum";
    $query4 = "SELECT classlist.accountID From classlist Where classID = $classNum";
    
    // query 1
    $result = pdo_query($pdo,$query1);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $schoolName = $row['name'];
    // end query 1
    //query 3
    $result = pdo_query($pdo,$query3);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $className =$row['name'];
    $gradeLevel=$row['gradeLevel'];
    //end query 3
    //query 4
    $result = pdo_query($pdo,$query4);
    $row = $result->fetch(PDO::FETCH_ASSOC); // has multile results;
    foreach ($row as $value) {
        //query 2
        $query2 = "SELECT accounts.name , accounts.email, accounts.password FROM accounts 
    WHERE accounts.ID = $value";
        $result = pdo_query($pdo,$query2);
        $newRow = $result->fetch(PDO::FETCH_ASSOC) ;
           $arr = array($newRow["name"], $newRow['email'], $newRow['password'],$className,$gradeLevel,"True");
           fputcsv($output, $arr); // phph function that saves to csv
        }  
    //end query 4
     fclose($output);
     $pdo = null;
     setcookie("exported", "true", time() + (60*1), "/"); 
        } catch (PDOException $e) {
       echo pdo_error($e);
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
    return "Error". $message;}
      

?>