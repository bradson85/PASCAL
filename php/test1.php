<?php 
require_once("../dbconfig.php");

$id = $_POST["uname"];
$password = $_POST["pwd"];

echo $password;
echo $id;
$pwd = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
// query to get all categries for drop down menu
    $sql = "UPDATE accounts SET password = '$pwd' WHERE ID ='$id'";
    $result = $pdo->query($sql);
     
           
  $pdo = null;
  } catch (PDOException $e) {
  die( $e->getMessage() );
  } 

?>