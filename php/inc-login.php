<?php
session_start();
 require_once("../dbconfig.php");
 include ('inc.functions.php');
 
 if(isset($_POST['email']))
 {
  resetPW($_POST['email']);
 }
 
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
   if(isset($_POST['submit']))
   {
    if($_POST['submit'] == 'true'){
   
      login();
      
      }
   }
  
   
 }

 function resetPW($email)
 {
  $guid = uniqid();
  $guid = hash("md5", $guid);

  $pdo = null;
  $pdo = pdo_construct();
  $date = date('Y-m-d h:m:s');
  //echo ("\n" . $date . "\n");
  $sql = "INSERT INTO password_change_requests (ID, time, userID) VALUES ('$guid', '$date', '$email')";

  $result = pdo_exec($pdo, $sql);

  // sends email to user
  if($result)
  {
      $to = $email;
      $subject = "Create Your Password";
      $txt = "You are recieving this message because the administrator has created you an account. Click here to complete your account set up: http://cafaprojectcs425.perado.tech/create-password.php?id=$guid";
      $headers = "FROM: Formative Assessment <s002722@siue.edu>";

      mail($to, $subject, $txt, $headers);
  }
 }
 
 
 function login(){
   global $admin,$teacher,$student;
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $checkLogin = checkLogin($_POST["email"],$_POST["psw"]);
      $success = $checkLogin[0];
      $accounttype = $checkLogin[1];
      $id = $checkLogin[2];
		if(!empty($_POST["email"])&& !empty($_POST["psw"]) && ($success)){
       
        $_SESSION["type"] = $accounttype;
        $_SESSION['ID'] = $id;
        $stringLink ="";
        switch($accounttype){
          case "1": 
         $_SESSION["class"] = getClassID($id);
          $_SESSION['school'] = getSchoolID($id);
          $stringLink = "../teacher-dashboard.php"; //to redirect back to "teacher-dashboard.php"
            break;
          case "0":
          $_SESSION["class"] = 999;
          $_SESSION['school'] = 999;
          $stringLink ="../dashboard.php"; //to redirect back to "dashboard.php"
            break;
   
          case "2":
          $_SESSION["class"] = getClassID($id);
          $_SESSION['school'] =getSchoolID($id);
          $stringLink ="../student-landing.php"; //to redirect back to "studentlanding.php"
            break;
   
          default: 
                   
       }
       $sel= array(0 => 2,
       1 => $stringLink
      );
      echo json_encode($sel);
    }
		else {
				
			 reShowLoginForm();
				}
	}
	else{
	
		showLogin();
		}
 		}


function showLogin(){
     echo ("<form method ='POST' action='php/processlogin.php'>
      <div class='form-group'>
        <label for='email'>Login:</label>
        <input type='email' class='form-control' id='email' name='email'>
        <p class='text-danger invisible'>Etiam porta sem malesuada magna mollis euismod.</p>
      </div>
      <div class='form-group'>
        <label for='pwd'>Password:</label>
        <input type='password' class='form-control' id='pwd' name='pwd'>
        <p class='text-danger invisible'>Etiam porta sem malesuada magna mollis euismod.</p>
      </div>
      <div class='checkbox'>
        <label><input type='checkbox'> Remember me</label>
      </div>
      <button type='submit' id='loginbutton' class='btn btn-primary'>Login</button>
    </form>
    <br>
    <p><a href='#' data-toggle='modal' data-target='#resetPWModal' id='resetPW'>Forgot your password?</a></p>
    
    <br> ". '<div class="modal fade" id="resetPWModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" placeholder="your@email.com"><br> <button class="btn btn-primary" id="resetSubmit">Submit</button> <span id="resetSuccess" hidden>An e-mail has been sent to you.</span>
        </div>
      </div>
    </div>
  </div>');
  }  
  function reShowLoginForm(){
       echo ("<form method ='POST' action='php/processlogin.php'>
      <div class='form-group'>
        <label for='email'>Login:</label>
        <input type='email' class='form-control' id='email'>
        <p class='text-danger'>Please enter the correct Login.</p>
      </div>
      <div class='form-group'>
        <label for='pwd'>Password:</label>
        <input type='password' class='form-control' id='pwd'>
        <p class='text-danger'>Please enter the correct Password.</p>
      </div>
      <div class='checkbox'>
        <label><input type='checkbox'> Remember me</label>
      </div>
      <button type='submit' id='loginbutton' class='btn btn-primary'>Login</button>
    </form>
    <br>
    <p><a href='#' data-toggle='modal' data-target='resetPWModal' id='resetPW'>Forgot your password?</a></p>
    <br>" . '<div class="modal fade" id="resetPWModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" placeholder="your@email.com"> <button class="btn btn-primary">Submit</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>');
  }
  
 
 		
 function checkLogin($username, $password){
 $accept = false;
$type = 3;
$ID = 0;
  try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT * FROM accounts";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
             if( ($username == $row['email'] || $username == $row['name'])
              && password_verify($password , $row['password'])){
              $_SESSION["user"] = $row["name"]; 
              $_SESSION["email"] =$row["email"]; 
               $ID = $row["ID"];
                  $accept = true;
                   $type = $row['type'];
                   
		break;
             }    
            }    
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
     
      $arr = array( 0 =>$accept,
      1 => $type,
      2 => $ID
      );
       
      return $arr;
      } 

      // checks to see which class  or classes a teacher or student belongs to.
      function getClassID($accountID){
                
        try {
          $pdo = new PDO(DB_CONNECTION_STRING,
          DB_USER, DB_PWD);
          $pdo->setAttribute(PDO::ATTR_ERRMODE,
          PDO::ERRMODE_EXCEPTION);
      // query to get all categries for drop down menu
          $sql = "SELECT classID FROM classlist WHERE accountID = \"$accountID\"";
          $result = $pdo->query($sql);
        $row = $result->fetchAll(PDO::FETCH_ASSOC) ;

               
        $pdo = null;
        } catch (PDOException $e) {
        die( $e->getMessage() );
        } 
         
        return $row;
        } 

    
        function getSchoolID($accountID){
           $classID = getClassID($accountID); 
           $school = array();
          try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
            foreach ($classID as $value) {
              $item = $value['classID'];
            $sql = ("SELECT DISTINCT schoolID FROM classes WHERE ID = \"$item\"");
            $result = $pdo->query($sql);
          $row = $result->fetch(PDO::FETCH_ASSOC) ;
               $school[] = $row["schoolID"];
            }
            unset($value);
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
           
          return($school[0]);
          } 

 		// this for checkign what assessment has been assigned to student;
   function checkStudentAssessment($id){
    $assessmentID = 0;
    try {
      $pdo = new PDO(DB_CONNECTION_STRING,
      DB_USER, DB_PWD);
      $pdo->setAttribute(PDO::ATTR_ERRMODE,
      PDO::ERRMODE_EXCEPTION);
  // query to get all categries for drop down menu
      $sql = "SELECT * FROM assessmentassignments";
      $result = $pdo->query($sql);
      while($row = $result->fetch(PDO::FETCH_ASSOC) ){
           if( $id == $row['studentID']){
            $assessmentID = $row["assessmentID"]; 
            
                 
  break;
           }    
          }    
    $pdo = null;
    } catch (PDOException $e) {
    die( $e->getMessage() );
    } 
     return $assessmentID;
   }
      
 ?>