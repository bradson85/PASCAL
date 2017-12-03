<?php
// this file updates the database to match the table recored on the webpage
require_once("../dbconfig.php");
include "inc-addwords-functions.php";

$success = 1; // used to detect if a category is not selected from dropdown menu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field and convert jason to array
    $TableData = json_decode(stripslashes($_POST['data']),true);
}
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
    for ($i=0; $i < count($TableData); $i++) {
        $email= $TableData[$i]['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Incorrect Email Address";
            return; 
          }
       
        $splitString = explode(' ',$TableData[$i]['category']); // splits the category and level from tables
        $category =  $splitString[0];                          // category is first of the split string
        $level = $splitString[1];                              // level is second of split screen
    
        $sql = $pdo->prepare("INSERT INTO assignedAssessments (studentID, assessmentID, Expiration, Link)
        Values (:sID , :aID, :exp , :link)");
       $sql->bindParam(':sID', $sID);
      $sql->bindParam(':aID', $aID);
      $sql->bindParam(':exp', $exp); 
      $sql->bindParam(':link', $link); 
       $sID = trim($TableData[$i]['student']," ");            // Trim To get rid of whitespace at begining and end
       $aID = trim(matchCatID($category, $level)," ");     // caused by html.
       $exp = trim($TableData[$i]['date']," ");
       $link = generateLink($sID,$aID);
      $sql->execute();
 }
}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
       
    echo $success;

    function matchAssessID($categoryName, $level){
        
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
     $sql = $pdo->prepare("SELECT assessments.ID From assessments WHERE assessments.catID = ( SELECT categories.ID WHERE categories.name = :catName AND categories.level = :level)");
    
     $sql->bindParam(':catName', $catName, PDO::PARAM_INT);
     $sql->bindParam(':level', $lev);
         $catName = $categoryName;
         $lev = $level;
         $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
              $value= $row["ID"];     
    
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
    return $value;
    }

    function generateLink($student,$assessment){
 
       
        $linkstring = "http://cafaprojectcs425.perado.tech/assessment.php?id=$assessment&student=$student";

        $to = $email;
        $subject = "Assessment Link";
        $txt = "You are recieving this message because a teacher has assigned you an account . Click here to take the assessment: $linkstring";
        $headers = "FROM: Formative Assessment <formassess-no-reply@siue.edu>";

        //mail($to, $subject, $txt, $headers);

        return $linkstring;
    }

?>