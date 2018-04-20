<?php 
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
if(isset($_POST['categoryChoice'])){
  
    if(strcmp($_POST['categoryChoice'],"all")==0){
        assembleAllWordTable();
    }else assembleWordTable($_POST['categoryChoice']);
}

function assembleAllWordTable(){
    $allCategories = getAllCategoryInfo();
    $body = "";
    foreach ($allCategories as $value) {
       
    $catID = $value['ID'];
    $termsList = getTermsData($catID);
    $data = compareTermsToResults($termsList,$value['name']." " . $value['level']);  
     $body .= createWordStatTableBody($data);    
    }
   
   echo createWordStatTable($body);
}

function assembleWordTable($catChoice){
    
    $level =  trim(substr($catChoice, -1));                        // category is first of the split string
    $category = trim(substr($catChoice, 0, -2));
    $catID = getCategoryID($category,$level);
    $termsList = getTermsData($catID);
   $data = compareTermsToResults($termsList,$catChoice);
   $body = createWordStatTableBody($data);
   echo createWordStatTable($body);
}

function compareTermsToResults($termsList,$catName){
    $returnData = array();
    $arr1 = array();
    foreach ($termsList as $value) {
       if(checkResultsExist($value['ID'])){
           $arr1['word'] = $value['name'];
           $arr1['cat'] = $catName;
           $successCount = getTermTestScoreData($value['ID']);
           $missedCount = getTermTestAttemptData($value["ID"]);
            $arr1['matches'] = $successCount ."/".($missedCount + $successCount);
           
            array_push($returnData, $arr1);
       }
    }
    unset($value);

  return $returnData;
}

function checkResultsExist($termID){
  $hasScore = false;
    try {
        $pdo = newPDO();
        $query = ("SELECT ID FROM results WHERE termID = '$termID'");
        $result = pdo_query($pdo,$query);
        $row = $result->fetch();
     if($row){
         $hasScore =true;
     }

    }
        catch(PDOException $e)
        {
            echo pdo_error($e);
         }
            $pdo = null;
        return $hasScore;

}

function getTermsData($categoryID){
    try {
        $pdo = newPDO();
        $query = ("SELECT ID, name FROM terms WHERE catID ='$categoryID'");
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

function getCategoryID($catName,$level){
    try {
        $pdo = newPDO();
        $query = ("SELECT ID FROM categories WHERE name = '$catName' AND level = '$level'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
       
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
return ($row["ID"]);
}

function createWordStatTableBody($data){
    $string = "";   
    foreach ($data as $value) {
        $string .= "<tr><td>".$value['word']."</td>
                     <td>".$value['cat']."</td>
                <td>".$value['matches']."</td></tr>";
     }
     unset($value);
     return $string;
}

function createWordStatTable($body){
$string = '
    <table class="table table-bordered" id="dataTableWords" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>Word</th>
          <th>Category And Level</th>
          <th>Total Word Matches Correct/Attempted</th>
        </tr>
      </thead>
      <tbody>'. $body .'
        </tbody>
    </table>';

return $string;
}

function getTermTestScoreData($termID){
    try {
        $pdo = newPDO();
        $query = ("SELECT count(ID) FROM results WHERE correct = 1 AND termID = '$termID' ");
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

function getTermTestAttemptData($termID){
    try {
        $pdo = newPDO();
        $query = ("SELECT count(ID) FROM results WHERE termID = '$termID' AND correct = 0");
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

function getAllCategoryInfo(){
    try {
        $pdo = newPDO();
        $query = "SELECT ID, name, level From categories ";
        $result = pdo_query($pdo,$query);
        $row = $result->fetchAll(PDO::FETCH_ASSOC); // has multile results;
        $pdo = null;
    } catch (PDOException $e) {
        echo pdo_error($e);
         }
         return $row;
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