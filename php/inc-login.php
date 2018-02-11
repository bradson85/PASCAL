<?php
session_start();
 require_once("../dbconfig.php");
 

 if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if($_POST['submit'] == 'true'){
   
   login();
   
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
        
        switch($accounttype){
          case "1": 
          header("Location:../teacher-dashboard.php"); //to redirect back to "teacher-dashboard.php"
            break;
          case "0":
          header("Location:../dashboard.php"); //to redirect back to "dashboard.php"
            break;
   
          case "2":
          $_SESSION["ID"] = $id;
          header("Location:../student-landing.php"); //to redirect back to "studentlanding.php"
            break;
   
          default: 
                   
       }
        
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
      <button type='submit' id='loginbutton' class='btn btn-default'>Submit</button>
    </form>
    <br> ");
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
      <button type='submit' id='loginbutton' class='btn btn-default'>Submit</button>
    </form>
    <br>");
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
             if( ($username == $row['email'] || $username == $row['name']) && password_verify($password , $row['password'])){
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