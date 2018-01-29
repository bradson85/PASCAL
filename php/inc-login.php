<?php
session_start();
 require_once("../dbconfig.php");
 $admin = false;
 $student = false;
 $teacher = false;

 if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if($_POST['submit'] == 'true'){
   
   login();
   
   }
 }
 
 
 function login(){
   global $admin,$teacher,$student;
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
		if(!empty($_POST["email"])&& !empty($_POST["psw"]) && (checkLogin($_POST["email"],$_POST["psw"]))){
        $_SESSION["user"] = $_POST["email"];
        if($student == true){
           echo "2";
        }else if($admin == true){
           echo "1";
        }else if($teacher== true){
          echo "3";
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
 global $admin, $teacher, $student;
  try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT * FROM admins";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
             if( ($username == $row['email'] || $username= $row['name']) & $password == $row['password']){
                   $accept = true;
                  $admin = true; 
                 $student = false;
                 $teacher = false;
		break;
             }    
              }
             $sql = "SELECT * FROM students";
             $result = $pdo->query($sql);
             while($row = $result->fetch(PDO::FETCH_ASSOC) ){
                  if( ($username == $ $username= $row['ID']) & $password == $row['password']){
                        $accept = true;
                         $admin = false; 
                         $student = true;
                         $teacher = false;
         break;
                  }  
                  
        }
        $sql = "SELECT * FROM teachers";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
             if( ($username == $row['email'] || $username= $row['name']) & $password == $row['password']){
                   $accept = true;
                   $admin = false; 
                    $student = false;
                    $teacher = true;
		break;
             }  
            }
      
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
     
        return $accept;
      }  
 		
 	
 ?>