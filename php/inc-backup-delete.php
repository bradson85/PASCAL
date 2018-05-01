<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
 
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
       
        $sql = ("SET foreign_key_checks = 0");
        $result = $pdo->query($sql);
       // print_r($result);
        $sql = 'SHOW Tables';
        
        $result = $pdo->query($sql);
         while ($row = $result->fetch(PDO::FETCH_NUM)) {
             // print_r( $row[0]);
              //echo(", ");
              if (strcasecmp($row[0],"accounts")==0){
                $sql = "DELETE FROM " .$row[0]. " WHERE ID <> 0;";
                $success = $pdo->exec($sql);
                //echo( "Deleted $success items from" . $row[0].".");
              }else{
              $sql = "DELETE FROM " .$row[0]. "";
              $success = $pdo->exec($sql);
             // echo( "Deleted $success items from" . $row[0].".");
                 }
                }
    
              $sql = ("SET foreign_key_checks = 1");
              $result = $pdo->query($sql);
               // print_r($result);
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 

            header('Location: /backupdb.php');
        exit;   

?>