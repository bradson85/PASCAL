<?php 
session_start(); 
if(isset($_SESSION['type']) ){
  include ("php/inc-dashboard.php"); // directed to right page
} else{
  echo "Unauthorized access";
  header("Location:index.php"); //to redirect back to "index.php" after logging out
  exit();
} 
?>