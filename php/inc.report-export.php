<?php
session_start();
require_once("../dbconfig.php");


if(isset($_POST['dataChoice'])){
$dataChoice = json_decode( $_POST['dataChoice']); // this is  an object


// save the column headers
if(strcasecmp($dataChoice->type,"allwordScores")==0){
    $titleArray = $dataChoice->checkArray;
    array_unshift($titleArray, "Word");
     createAllWordsReport($dataChoice->category,$titleArray);
    
}else if (strcasecmp($dataChoice->type,"studentWordScores")==0){
    $titleArray = $dataChoice->checkArray;
  
    if(strcasecmp($dataChoice->studentEmail, -1)== 0){
         if(strcasecmp($dataChoice->specialCheck,"0")==0){
            array_unshift($titleArray, "Word");
            createAllWordsReportPerClass($dataChoice->category,$titleArray,$dataChoice->classID);
         }else {
            createAllClassStudentsPerWordsReport($dataChoice->category,$dataChoice->classID);
         }
    } else{ $ID = getAccountIDFromEmail($dataChoice->studentEmail);
        createOneStudentsPerWordsReport($dataChoice->category,$ID);
    } 
}else if(strcasecmp($dataChoice->type,"assessmentScores")==0){
       createAllAssessmentReport($dataChoice->studentEmail,$dataChoice->classID);
   
}
    
    } else  echo "Error No Data Sent";   
 

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






function createAllWordsReport($category,$reportChoices){

    if(strcmp($category,"all")==0){
        assembleAllWords($reportChoices);
    }else  assembleSpecificWords($category,$reportChoices);
}

function createAllWordsReportPerClass($category,$reportChoices,$classID){

    if(strcmp($category,"all")==0){
        assembleSomeWords($reportChoices,$classID);
    }else  assembleSpecificClassWords($category,$reportChoices,$classID);
}

function createAllClassStudentsPerWordsReport($category,$classID){

    if(strcmp($category,"all")==0){
        assembleWordsPerStudentForClass($classID);
    }else  assembleSpecificWordsPerStudentForClass($category,$classID);
}

function createOneStudentsPerWordsReport($category,$ID){

    if(strcmp($category,"all")==0){
        assembleWordsForOneStudent($ID);
    }else  assembleSpecificWordsForOneStudent($category,$ID);
}

function  createAllAssessmentReport($email,$classID){
    if($email== -1){
     assembleAssessmentsForAllStudents($classID);
    } else {
        $ID = getAccountIDFromEmail($email);
        assembleAssessmentsForOneStudent($ID);
    }
}

function assembleAllWords($reportChoices){
    $allCategories = getAllCategoryInfo();
   $options = determineOptionSelected($reportChoices);
    // get terms Data and match it with test data
    $arr = array();
    $returnArray = array();
    foreach ($allCategories as $value) {
    $catID = $value['ID'];
    $termsList = getTermsData($catID);
    $data = compareTermsToResults($termsList,$value['name']." ".$value['level']); 
       array_push($arr, $data); 
    }unset($value);
    $prepareddata = createFinalArray($options,$arr);
    array_unshift($prepareddata,$reportChoices);
    $body = createWordStatTableBody($prepareddata);
    $table = createWordStatTable($body,$reportChoices,0);
    $prepareddata = json_encode($prepareddata);
    $prepareddata = str_replace("'", "\'", $prepareddata);
    $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
    $form = createform($prepareddata);
    echo $form . " " .$table ;
}

function assembleSpecificWords($catChoice,$reportChoices){
    $options = determineOptionSelected($reportChoices);
    $level =  trim(substr($catChoice, -1));                        // category is first of the split string
    $category = trim(substr($catChoice, 0, -2));
    $catID = getCategoryID($category,$level);
    $termsList = getTermsData($catID);
   $data[0] = compareTermsToResults($termsList,$catChoice); // use zero becaus of create final fucntion
   $prepareddata = createFinalArray($options,$data);
   array_unshift($prepareddata,$reportChoices);
   $body = createWordStatTableBody($prepareddata);
   $table = createWordStatTable($body,$reportChoices,0);
   $prepareddata = json_encode($prepareddata);
   $prepareddata = str_replace("'", "\'", $prepareddata);
   $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
   $form = createform($prepareddata);
   echo $form . " " .$table ;
}

// for class or indvidual owrd stats
function assembleSomeWords($reportChoices,$classID){
    $allCategories = getAllCategoryInfo();
   $options = determineOptionSelected($reportChoices);
   $classList =getClassListAccountID($classID);
   $classInfo = getClassInfoFromClassID($classID);
   $className = $classInfo['name'];
    // get terms Data and match it with test data
    $arr = array();
    $returnArray = array();
    foreach ($allCategories as $value) {
    $catID = $value['ID'];
    $termsList = getTermsData($catID);
    $data = compareClassListTermsToResults($classList,$termsList,$value['name']." ".$value['level']); 
       array_push($arr, $data);
    }unset($value);
    $prepareddata = createFinalArray($options,$arr);
    array_unshift($prepareddata,$reportChoices);
    $body = createWordStatTableBody($prepareddata);
    $table = createWordStatTable($body,$reportChoices,$className);
    $prepareddata = json_encode($prepareddata);
    $prepareddata = str_replace("'", "\'", $prepareddata);
    $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
    $form = createform($prepareddata);
    echo $form . " " .$table ;
}

function assembleSpecificClassWords($catChoice,$reportChoices,$classID){
    $options = determineOptionSelected($reportChoices);
    $level =  trim(substr($catChoice, -1));                        // category is first of the split string
    $category = trim(substr($catChoice, 0, -2));
    $catID = getCategoryID($category,$level);
    $classList =getClassListAccountID($classID);
    $classInfo = getClassInfoFromClassID($classID);
    $className = $classInfo['name'];
    $termsList = getTermsData($catID);
    $data[0] = compareClassListTermsToResults($classList, $termsList,$catChoice); 
    $prepareddata = createFinalArray($options,$data);
   array_unshift($prepareddata,$reportChoices);
   $body = createWordStatTableBody($prepareddata);
   $table = createWordStatTable($body,$reportChoices,$className);
   $prepareddata = json_encode($prepareddata);
    $prepareddata = str_replace("'", "\'", $prepareddata);
    $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
    $form = createform($prepareddata);
   echo $form . " " .$table ;
}

function assembleWordsPerStudentForClass($classID){
   $options = array("name","matches");
   $classList =getClassListAccountID($classID);
   $classInfo = getClassInfoFromClassID($classID);
   $className = $classInfo['name'];
    // get terms Data and match it with test data
    $arr = array();
    $returnArray = array();
    $catID = $value['ID'];
    $termsList = getAllTermsData();
    $data = compareClassListTermsToResultsInHorizontal($classList,$termsList); 
   $prepareddata = createFinalHorizArray($data);
   $body = createWordStatTableBody($prepareddata);
   $table = createWordStatTable($body,$reportChoices,$className);
   $prepareddata = json_encode($prepareddata);
   $prepareddata = str_replace("'", "\'", $prepareddata);
   $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
   $form = createform($prepareddata);
    echo $form . " " .$table ;
}

function assembleSpecificWordsPerStudentForClass($catChoice,$classID){
   // $options = determineOptionSelected($reportChoices);
    $level =  trim(substr($catChoice, -1));                        // category is first of the split string
    $category = trim(substr($catChoice, 0, -2));
    $catID = getCategoryID($category,$level);
    $classList =getClassListAccountID($classID);
    $classInfo = getClassInfoFromClassID($classID);
    $className = $classInfo['name'];
    $termsList = getTermsData($catID);
    $data = compareClassListTermsToResultsInHorizontal($classList,$termsList); 
    $prepareddata = createFinalHorizArray($data);;
    $body = createWordStatTableBody($prepareddata);
    $table = createWordStatTable($body,$reportChoices,$className);
    $prepareddata = json_encode($prepareddata);
    $prepareddata = str_replace("'", "\'", $prepareddata);
    $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
    $form = createform($prepareddata);
     echo $form . " " .$table ;
}

function assembleWordsForOneStudent($ID){
     $termsList = getAllTermsData();
     $classList[0] = $ID;
     $data = compareClassListTermsToResultsInHorizontal($classList,$termsList); 
    $prepareddata = createFinalHorizArray($data);;
    $body = createWordStatTableBody($prepareddata);
    $table = createWordStatTable($body,$reportChoices,$className);
    $prepareddata = json_encode($prepareddata);
    $prepareddata = str_replace("'", "\'", $prepareddata);
    $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
    $form = createform($prepareddata);
     echo $form . " " .$table ;
 }
 
 function assembleSpecificWordsForOneStudent($catChoice,$ID){
    // $options = determineOptionSelected($reportChoices);
     $level =  trim(substr($catChoice, -1));                        // category is first of the split string
     $category = trim(substr($catChoice, 0, -2));
     $catID = getCategoryID($category,$level);
     $classList[0] = $ID;
     $termsList = getTermsData($catID);
     $data = compareClassListTermsToResultsInHorizontal($classList,$termsList); 
     $prepareddata = createFinalHorizArray($data);;
     $body = createWordStatTableBody($prepareddata);
     $table = createWordStatTable($body,$reportChoices,$className);
     $prepareddata = json_encode($prepareddata);
     $prepareddata = str_replace("'", "\'", $prepareddata);
     $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
     $form = createform($prepareddata);
      echo $form . " " .$table ;
 }

 function assembleAssessmentsForOneStudent($ID){
    // $options = determineOptionSelected($reportChoices);
    $classList[0] = $ID;
     $assessmentList = getAllAssessments();
     $data = compareClassListAssessmentsToResultsInHorizontal($classList,$assessmentList); 
     $prepareddata = createFinalHorizArray($data);;
     $body = createWordStatTableBody($prepareddata);
     $table = createWordStatTable($body,$reportChoices,$className);
     $prepareddata = json_encode($prepareddata);
     $prepareddata = str_replace("'", "\'", $prepareddata);
     $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
     $form = createform($prepareddata);
      echo $form . " " .$table ;
 }

 function assembleAssessmentsForAllStudents($classID){
    // $options = determineOptionSelected($reportChoices);
    $classList =getClassListAccountID($classID);
    $classInfo = getClassInfoFromClassID($classID);
    $className = $classInfo['name'];
     $assessmentList = getAllAssessments();
     $data = compareClassListAssessmentsToResultsInHorizontal($classList,$assessmentList); 
     $prepareddata = createFinalHorizArray($data);;
     $body = createWordStatTableBody($prepareddata);
     $table = createWordStatTable($body,$reportChoices,$className);
     $prepareddata = json_encode($prepareddata);
     $prepareddata = str_replace("'", "\'", $prepareddata);
     $prepareddata = str_replace('"', "'+String.fromCharCode(34)+'", $prepareddata);
     $form = createform($prepareddata);
      echo $form . " " .$table ;
 }

function createform($data){
    $tempData =json_decode( $_POST['dataChoice']);
    if(strcasecmp($tempData->format,"csv")==0){
   $form = '<form action="php/inc.report-exportCSV.php" method="post">
 
  <input type="hidden" name="data" value="'.$data.'"><br>
  
  <button id="submit" type="submit" class="btn btn-primary">Download CSV File</button>
</form>';
    } else if (strcasecmp($tempData->format,"pdf")==0){

        $form = '<form action="php/inc.report-exportPDF.php" target="_blank" method="post">
 
        <input type="hidden" name="data" value="'.$data.'"><br>
        
        <button id="submit" type="submit" class="btn btn-primary">Download PDF File</button>
      </form>';
    }
    return $form;
}
function compareTermsToResults($termsList,$catName){
    $returnData = array();
    $arr1 = array();
    foreach ($termsList as $value) {
       if(checkResultsExist($value['ID'])){
           $arr1['word'] = $value['name'];
           $termsMatched =getTermTestScoreData($value['ID']);
           $termsAttempted = getTermTestAttemptData($value["ID"]) ;
           $arr1['average'] = getTermAverage($termsMatched, ($termsAttempted + $termsMatched));
           $arr1['cat'] = $catName;
            $arr1['matches'] = $termsMatched ." of ".($termsAttempted + $termsMatched );
            $arr1['def'] = $value['definition'];
            array_push($returnData, $arr1);
       }
    }
    unset($value);

  return $returnData;
}

function compareClassListTermsToResults($classList,$termsList,$catName){
    $returnData = array();
    $arr1 = array();
    $termsMatched = 0; // palce at top for global count
    $termsAttempted = 0;
    foreach ($termsList as $value) {
        $count = 0;   // starts count over for each term
        foreach ($classList as $index) {
            $email = getAccountEmailFromID($index['accountID']);
       if(checkResultsExistForStudent($value['ID'],$email)){
           $count++; // if a result iexist for multiple accounts increment
           if($count >1){
            $termsMatched +=getTestTermDataByStudent($email,$value['ID']);
            $termsAttempted += getTermTestAttemptDataByStudent($email,$value["ID"]) ;
            array_pop($returnData);
           }else {
           $termsMatched =getTestTermDataByStudent($email,$value['ID']);
           $termsAttempted = getTermTestAttemptDataByStudent($email,$value["ID"]) ;
           }
           $arr1['word'] = $value['name'];
           $arr1['def'] = $value['definition'];
           $arr1['cat'] = $catName;
           $arr1['average'] = getTermAverage($termsMatched, ($termsAttempted + $termsMatched));
           $arr1['matches'] = $termsMatched ." of ".($termsAttempted + $termsMatched );
           $returnData[] = $arr1;
        }
    } unset($index);
   
} unset($value);
  return $returnData;
}

function compareClassListTermsToResultsInHorizontal($classList,$termsList){
    $returnData = array();
    $title[0] = ""; 
     foreach($classList as $student){
        $arr1 = array();
        $email = getAccountEmailFromID($student['accountID']);
        $name =getAccountNameFromEmail($email);
         foreach($termsList as $term){
            if(checkResultsExistForStudent($term['ID'],$email)){
                $title[] = $term['name'];
                $arr1['name'] = $name;
                $termsMatched =getTestTermDataByStudent($email,$term['ID']);
                $termsAttempted = getTermTestAttemptDataByStudent($email,$term["ID"]) ;
                $arr1[$term["name"]] = $termsMatched ." of ".($termsAttempted + $termsMatched );
                
            }
         }unset($term);
         $returnData[] = $arr1;
     }unset($student);
     array_unshift($returnData,$title);
   
return $returnData;
}

function compareClassListAssessmentsToResultsInHorizontal($classList,$assessList){
    $returnData = array();
    $title[0] = ""; 
     foreach($classList as $student){
        $arr1 = array();
        $email = getAccountEmailFromID($student['accountID']);
        $name =getAccountNameFromEmail($email);
         foreach($assessList as $assess){
            if(checkAssessmentExistForStudent($assess['ID'],$email)){
                $catResult = getCatInfoFromCatID($assess['catID']);
                $level =  $catResult['level'];                        // category is first of the split string
                $category = $catResult['name'];
                $catstring = $category. " ". $level . "( ID:". $assess['ID'] .")";
                $title[] = $catstring;
                $arr1['name'] = $name;
                $termsMatched =getStudentAssessmentScoreData($email,$assess['ID']);
                $termsAttempted = getStudentAssessmentMissedData($email,$assess["ID"]) ;
                $arr1[$catstring] = $termsMatched ." of ".($termsAttempted + $termsMatched );
               
            }
         }unset($term);
         $returnData[] = $arr1;
     }unset($student);
     array_unshift($returnData,$title);
   
return($returnData);
}

function createFinalArray($options,$dbData){
    $returnArray = array();
    $termCount = 0; // running index of terms
    foreach ($dbData as $index) {
        foreach ($index as $term) {
            foreach($options as $value){
                 $returnArray[$termCount][$value]= $term[$value];
                }unset($value);
            $termCount++;  // every time a term is found increment in term loop
            }unset($term);
}unset($index);
return $returnArray;
}

function createFinalHorizArray($dbData){
    // index 0 is title data 
    $infoArray = array();
    $titleArray = (array_unique($dbData[0])); // clean out duplicates
   foreach ($dbData as $key => $value) {
       if(isset($value['name'])){
       foreach ($titleArray as $key2=> $index) {
       
          if(isset($value[$index])){
            $infoArray[$key][0] = $value['name'];
              $infoArray[$key][] = ($value[$index]) ;
          } else $infoArray[$key][] = "";
       }
   }
}
     $finalArray = $infoArray;
     array_unshift($finalArray,$titleArray); 
    return($finalArray);
}



/// get the right db term for approiate option
function determineOptionSelected($items){
    $arrString = array();
    foreach ($items as $value) {
switch ($value){
    case "Word":
        array_push($arrString, 'word');
        break;
    case "Student Name":
        array_push($arrString, 'name');
        break;
    case "Average Score":
        array_push($arrString, 'average');
        break;
    case "Total Matches/Attempts":
    array_push($arrString, 'matches');
        break;
    case "Category-Level":
    array_push($arrString, 'cat');
        break;
    case "Defintion":
    array_push($arrString, 'def');
        break;
    case "GradeLevel":
    array_push($arrString, 'grade');
        break;
    case "Best Score":
    array_push($arrString, 'bestScore');
        break;
    case "Last Completed Assessment":
    array_push($arrString, 'lastCompleted');
        break;
    
    default:
       
}
}
 return $arrString;
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

function checkResultsExistForStudent($termID,$email){
    $hasScore = false;
      try {
          $pdo = newPDO();
          $query = ("SELECT r.ID FROM results AS r, accounts AS s WHERE r.termID = '$termID' AND s.email = '$email' AND s.ID = r.studentID");
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

  function checkAssessmentExistForStudent($assessID,$email){
    $hasScore = false;
      try {
          $pdo = newPDO();
          $query = ("SELECT r.ID FROM results AS r, accounts AS s WHERE r.assessmentID = $assessID AND s.email = '$email' AND s.ID = r.studentID");
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
        $query = ("SELECT ID, name, definition FROM terms WHERE catID ='$categoryID'");
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

function getAllTermsData(){
    try {
        $pdo = newPDO();
        $query = ("SELECT ID, name, definition FROM terms");
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
        $string .= "<tr>";
      foreach($value as $index){
          $string .= "<td>$index</td>";
      } unset($index);
        $string .= '</tr>';
     }
     unset($value);
     return $string;
}

function createWordStatTable($body, $headers,$title){
    $string = "";
    if(!(strcmp($title,"0") == 0)){
        $string .= "<div><h5>$title</h5></div>"; 
    }
    $string .= "<table class='table table-bordered' id='dataTableWords' width='100%' cellspacing='0'>
          <thead>
            <tr> </tr>
        </thead>
          <tbody> $body 
            </tbody>
        </table>";
    
    return $string;
    }

    function getTestTermDataByStudent($email,$termID){
        try {
            $pdo = newPDO();
            $query = ("SELECT count(r.ID) FROM results AS r, accounts AS s WHERE r.termID = '$termID' AND r.correct = 1 AND s.email = '$email' AND s.ID = r.studentID");
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
        $query = ("SELECT count(ID) FROM results WHERE termID = '$termID' AND correct = 0 ");
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

function getTermTestAttemptDataByStudent($email,$termID){
    try {
        $pdo = newPDO();
        $query = ("SELECT count(r.ID) FROM results AS r, accounts AS s WHERE r.termID = '$termID' AND r.correct = 0 AND s.email = '$email' AND s.ID = r.studentID");
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

function getTermAverage($value1, $value2){
    
   
$average = $value1/$value2;
if(is_nan($average)){ // if not a number
    $average = "Calculation Error";
} 
return number_format(($average * 100), 2, '.', ',') . "%";
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

function getAllAssessments(){
    try {
        $pdo = newPDO();
        $query = "SELECT ID, catID, classID From assessments ";
        $result = pdo_query($pdo,$query);
        $row = $result->fetchAll(PDO::FETCH_ASSOC); // has multile results;
        $pdo = null;
    } catch (PDOException $e) {
        echo pdo_error($e);
         }
         return $row;
}

     
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


function getAccountNameFromEmail($email){
    try {
    $pdo = newPDO();
    $query = "SELECT name FROM accounts 
    WHERE email = '$email'";
    $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC) ;
        $pdo = null;
        } catch (PDOException $e) {
            echo pdo_error($e);
             }
             return $row['name'];
}

function getAccountIDFromEmail($email){
    try {
    $pdo = newPDO();
    $query = "SELECT ID FROM accounts 
    WHERE email = '$email'";
    $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC) ;
        $pdo = null;
        } catch (PDOException $e) {
            echo pdo_error($e);
             }
             return $row['ID'];
}

function getAccountEmailFromID($id){
    try {
    $pdo = newPDO();
    $query = "SELECT email FROM accounts 
    WHERE ID = $id";
    $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC) ;
        $pdo = null;
        } catch (PDOException $e) {
            echo pdo_error($e);
             }
             return $row['email'];
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

function getCatInfoFromCatID($id){
    try {
        $pdo = newPDO();
        $query= "SELECT name, level FROM categories WHERE ID = '$id'";
        $result = pdo_query($pdo,$query);
        $row = $result->fetch(PDO::FETCH_ASSOC); // has multile results;
        $pdo = null;
    } catch (PDOException $e) {
        echo pdo_error($e);
         }
         return $row;

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

function getStudentAssessmentScoreData($email,$assessID){
    try {
        $pdo = newPDO();
        $query = ("SELECT count(r.ID) FROM results AS r, accounts AS s WHERE r.assessmentID = $assessID AND r.correct = 1 AND s.email = '$email' AND s.ID = r.studentID");
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

function getStudentAssessmentMissedData($email,$assessID){
    try {
        $pdo = newPDO();
        $query = ("SELECT count(r.ID) FROM results AS r, accounts AS s WHERE r.assessmentID = $assessID AND r.correct = 0  AND s.email = '$email' AND s.ID = r.studentID");
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

?>