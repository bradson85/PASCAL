<?php 
session_start(); 
if(isset($_SESSION['type']) && $_SESSION['type'] == 1 ){
  include ("php/inc-teacher-dashboard.php"); // directed to right page
} else{
  echo "Unauthorized access";
  header("location:index.php"); //to redirect back to "index.php" after logging out
  exit();
}
?>