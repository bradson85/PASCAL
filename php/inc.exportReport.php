<?php

require_once("../dbconfig.php");


function csvExport(){
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=exportData.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
//fputcsv($output, array('School Name'));
// run query that exports in the format     school name
 
    } 
   
?>