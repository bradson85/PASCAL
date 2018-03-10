
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
if(isset($_SESSION['ID'])){
$id = $_SESSION['ID'];
$assignmentID = assignedAssessments($id);
$infoarray = getAssessmentData($assignmentID);

echo "<tr><td>" .$infoarray["category"]. "</td><td>". $infoarray["start"]."</td>   
<td>". $infoarray["end"]."</td>
<td>". $infoarray["class"]."</td></tr>" ;
}
function getButton(){

    if(isset($_SESSION['ID'])){
    $id = $_SESSION['ID'];
$assignmentID = assignedAssessments($id);
    echo "<form action='../assessment.php' method='GET'>
      <input type='hidden' name='id' value=$assignmentID /> 
      <input type='hidden' name='student' value=$id />
        <input type='submit' class='btn btn-primary btn-lg' value='Take Assessment' />
    </form>" ;
}
    }
function assignedAssessments($id){
    
    $assessmentID = 0;
    $date = strtotime("now");
    $finalID = 0;
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT assessmentID FROM assessmentassignments Where studentID = '$id'";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $assessmentID = $row["assessmentID"];   
            $date1 = getAssessmentDate($assessmentID);
            if ($date1 < $date){
                $date = $date1;
                $finalID = $assessmentID;
            }
        }
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
    return $finalID;
}

function getAssessmentDate($id){
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT start_date FROM assessments Where ID = '$id'";
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
            $date = $row["start_date"];   
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
    return $date;

}


function getAssessmentData($id){
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT * FROM assessments where ID = '$id'";
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
            $start = $row["start_date"];  
            $end = $row["end_date"];
            $class = getClassName($row['classID']);
            $category = getCategoryName($row['catID']);
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 

      $arr = array( "category" =>$category,
      "class" => $class,
      "end" => $end,
      "start" => $start
      );
       
      return($arr);

}


function getClassName($id){
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all classes 
        $sql = "SELECT name FROM classes where ID = '$id'";
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
            $classname = $row["name"];   
                
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
 return $classname;
}

function getCategoryName($id){
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query 
        $sql = "SELECT name, level  FROM categories where ID = '$id'";
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
           // $level = $row["level"];   
            $name = $row['name'];    
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
    return $name;

}

?>