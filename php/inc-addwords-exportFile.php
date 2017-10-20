<?php

require_once("../db/dbconfig.php");

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=export.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('ID', 'Word', 'Definition','CategoryID'));

 try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    $sql = 'SELECT * FROM terms';
    $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
     
    }
         
        $pdo = null;
        } catch (PDOException $e) {
        die( $e->getMessage() );
        } 
    
   
?>