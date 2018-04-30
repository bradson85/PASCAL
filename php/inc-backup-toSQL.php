<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");
 $CONFIGUREDIR = "/Applications/MAMP/Library/bin/mysqldump"; //location of mysql dump
 
     $dir = 'dbbackups/dbbackup'.date('d-m-Y-H-i-s');
    exec("$CONFIGUREDIR --user=root --password=root --host=localhost formassess> ".$dir.".sql", $outtext);

   

    header('Content-Type: text/plain; charset=utf-8');
     header('Content-Disposition: attachment; filename=pascalBackupData.sql');

     $myfile = fopen($dir, "r") or die("Unable to open file!");
$out= fread($myfile,filesize($dir));
fclose($myfile);
     $output = fopen('php://output', 'w');
    fwrite($output, 'EntireDB');
    fwrite($output,$out);
     //Export the database and output the status to the page
     exit;
 
?>