<?php

require_once("../dbconfig.php");

// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=pascalexport.csv');
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
// IE fix (for HTTPS only) header('Cache-Control: private');
header('Pragma: private');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
// save the column headers
fputcsv($output, array('Term', 'Definition','Origin', 'Category', 'Grade Level'));
// run query that exports in the format     word , definition , category grade level , category grade.
 try { 
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    $sql = 'SELECT terms.name AS "Key Term", terms.definition AS "Definition", 
    categories.name AS "Category" , categories.level AS "Gradelevel"
    FROM terms INNER JOIN categories ON terms.catID = categories.ID';
    
    $result = $pdo->query($sql);
     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
         $arr = array($row["Key Term"], $row['Definition'],'n/A', $row['Category'], $row['Gradelevel']);
        fputcsv($output, $arr); // phph function that saves to csv
     
    }
    fclose($output);
        $pdo = null;
        echo "Export Successful";
        } catch (PDOException $e) {
        die( $e->getMessage() );
        echo $e;
        } 
    
   
?>