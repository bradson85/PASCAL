<?php
session_start();
require_once("../dbconfig.php");

$dataChoice =  $_POST['data']; // this is  an object
$dataChoice = str_replace("\'", "'", $dataChoice);
$dataChoice = str_replace("'+String.fromCharCode(34)+'", '"' , $dataChoice);
$dataChoice = json_decode($dataChoice,true);
// output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reportData.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// save the column header

  foreach ($dataChoice  as $value) {
        
      fputcsv($output, $value); // phph function that saves to csv
           
      } 
   fclose($output);
  

     exit();
?>