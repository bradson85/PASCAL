<?php

require_once("../dbconfig.php");

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=allClasses.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
// save the column headers
fputcsv($output, array('Class Name', 'Grade Level', 'School ID'));
// run query that exports in the format     word , definition , category name , category level.
 try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    $sql = 'SELECT name, gradeLevel, schoolID FROM classes';
    
    $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row); // phph function that saves to csv
     
    }
        
        $pdo = null;
        } catch (PDOException $e) {
        die( $e->getMessage() );
        } 
    
   
?>