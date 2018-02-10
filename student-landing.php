<?php 
session_start(); 
if(isset($_SESSION['type']) && checktype() ){
  include ("php/inc-dashboard.php"); // directed to right page
} else{
  echo "Unauthorized access";
  header("location:index.php"); //to redirect back to "index.php" after logging out
  exit();
} 


function checktype(){
    switch($_SESSION["type"]){
       case 1: 
              return false;
       break;

       case 0:
               return false;
       break;

       case 2:
               return true;

       break;

       default: 
                return false;
    }
}
?>