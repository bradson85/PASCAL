<?php 
session_start(); 
if(isset($_SESSION['type']) ){
  include $_SERVER['DOCUMENT_ROOT'].'/php/inc-student-landing.php'; // directed to right page
} else{
  echo "Unauthorized access";
  header("Location:index.php"); //to redirect back to "index.php" after logging out
  exit();
} 
?>