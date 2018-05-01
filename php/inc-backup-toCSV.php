<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
 
       
    // output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=pascalBackupData.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('EntireDB'));
// run query that exports in the format     school name
 try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    $sql = 'SHOW Tables';
    
    $result = $pdo->query($sql);
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        fputcsv($output, $row); // phph function that saves to csv
        $sql = "SELECT * From " .$row['0']. "";
        $result2 = $pdo->query($sql);
           while($row2 = $result2->fetch(PDO::FETCH_ASSOC)){
            fputcsv($output, $row2);
           }
    }
        
        $pdo = null;
        } catch (PDOException $e) {
        die( $e->getMessage() );
        } 
       return("finished");
        exit;

 
 

?>