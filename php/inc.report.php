<?php
session_start();
    require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");


    if(isset($_POST['checkBoxType'])){
        $labels = $_POST['checkBoxType']; 
        $values =$_POST['checkBoxValues'];
             echo assembleCheckBoxes($labels,$values);
    } else if(isset($_POST['typeSelect'])){
        $option = $_POST['typeSelect']; 
             echo assembleTypeSelect();
    } else if(isset($_POST['classSelect'])){
        $option = $_POST['classSelect']; 
             echo assembleClassSelect($option);
    }
    else if(isset($_POST['schoolSelect']))
    {
       echo assembleSchoolSelect();
    }
    else if(isset($_POST['studentSelect']))
    {
       echo assembleStudentSelect($_POST['studentSelect']);

    }else if(isset($_POST['scoreChoice']))
    { 
           // need to build branches for each column header and each file choice.
        if(strcmp($_POST['scoreChoice'],"-1")==0){

            $classID = $_POST['classNumber'];
           $classList = getClassList($classID);
           $arr = getClassStudentScores($classList); // this returns all sutdents average.
           print_r($arr);
         } else {
            $email = $_POST['scoreChoice'];
            $allAssessments = getAllAssessments();
            $arr[0] = 0;
            foreach ($allAssessments as $value) {
                if(checkIfResultsExist($email,$value['ID'])){
               $arr[$value['ID']] = getStudentTestData($email,$value['ID']); // this test score for an individual
                }
            }
        
        unset($value);
           // echo json_encode($arr);
    }
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
     
     function assembleTypeSelect(){

       
         $selectString = "<option disabled selected value = \"0\"> Select A Type</option>
         <option  value = \"allwordScores\">All Word Data For All Tests</option>
         <option  value = \"studentwordScores\">Word Data By Students and Classes </option>
         <option  value = \"assessmentScores\">Student Results For All Assessments</option>";
         
         
         unset($value); //php manuel says do this after for each
         return $selectString;
         
         }


    function assembleSchoolSelect(){
        $selectString = "<option disabled selected value = \"0\"> Select A School</option>";
        $school = getSchoolList();
        foreach ($school as $value) {
            $schoolname = $value["name"];
            $selectString.= "<option value = \"".$value["ID"]."\"> $schoolname</option>";
        }
        unset($value); //php manuel says do this after for each
        return $selectString;
        
        }

        function assembleClassSelect($school){

            $schoolinfo = getClassInfoFromSchool($school);
             $selectString = "<option disabled selected value = \"0\"> Select A Class</option>";
             
             foreach ($schoolinfo as $value) {
                 $classname = $value["name"];
                 $selectString.= "<option value = \"".$value["ID"]."\"> $classname</option>";
             }
             unset($value); //php manuel says do this after for each
             return $selectString;
             
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

            function assembleStudentSelect($classID){
                $studentList = studentListTable($classID);  // all the acount ID's from of student in this calss
                $selectString = "<option disabled selected value = \"0\"> Select A Student</option>
                                <option  value = \"-1\">Entire Class</option>";
            
                foreach ($studentList as $value) {
                    if (!checkForTeacher($value["accountID"])){
                        $info = getStudentInfo($value["accountID"]);
                        $email= $info['email'];
                        $studentName = $info['name'];
                        
                        $selectString.= "<option value = '$email'> $studentName</option>";
                        }
                
                }
                unset($value); //php manuel says do this after for each
                return $selectString;
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
    
    function assembleCheckBoxes($labels,$values){
      $string ="";
      foreach($labels as $key =>$index){
     $string .= 
        '<div class="form-check form-check-inline">
                    <input class="form-check-input check1" type="checkbox" id="inlineCheckbox'.$key.'" value="'.$values[$key].'" >
                    <label class="form-check-label" for="inlineCheckbox'.$key.'">'.$index.'</label>
        </div>';

      }unset($index);
      return $string;
    }
    
    
    function getAverage($arr){
    
        $a = array_filter($arr);
    $average = array_sum($a)/count($a);
    return number_format(($average * 100), 2, '.', ',') . "%";
    }
    
?>