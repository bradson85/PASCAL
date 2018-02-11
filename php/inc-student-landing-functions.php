
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
echo"here";
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
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT assessmentID FROM assessmentassignments Where studentID = '$id'";
        $result = $pdo->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
            $assessmentID = $row["assessmentID"];   
            
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
    return $assessmentID;
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
            $start = $row["assessmentID"];   
            $end = $row["assessmentID"];
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
       
      return $arr;

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