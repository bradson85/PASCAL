<?php

// this returns all the categories in the database and places them inside of an a select option html dropdown
require_once("../dbconfig.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$sel= array();
$ID = json_decode(stripslashes($_POST['IDs']),true);
for ($i=0; $i < count($ID); $i++) {
array_push($sel,getLevel($ID[$i]['ID']));
}
echo json_encode($sel);
}

function getLevel($id){
try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
       
        $sql = $pdo->prepare("SELECT level From categories WHERE ID = :ID");
        $sql->bindParam(':ID', $id,PDO::PARAM_INT);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC) ;
            $level = $row["level"];
        
        $pdo = null;
            
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
           return $level;
        }  
    
?>