<?php

// this returns all the categories in the database and places them inside of an a select option html dropdown
require_once("../dbconfig.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sel= array();
    $ID = json_decode(stripslashes($_POST['IDs']),true);
    for ($i=0; $i < count($ID); $i++) {
    array_push($sel,getSchool($ID[$i]['ID']));
    }
    echo json_encode($sel);
    }
    
    function getSchool($id){
    try {
            $pdo = new PDO(DB_CONNECTION_STRING,
            DB_USER, DB_PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
           
            $sql = $pdo->prepare("SELECT schools.name AS schoolName 
            FROM schools INNER JOIN classes ON schools.ID = classes.schoolID");
            $sql->bindParam(':ID', $id,PDO::PARAM_INT);
            $sql->execute();
            $row = $sql->fetch(PDO::FETCH_ASSOC) ;
                $school = $row["schoolName"];
            
            $pdo = null;
                
                } catch (PDOException $e) {
                die( $e->getMessage() );
                } 
               return $school;
            }  
?>