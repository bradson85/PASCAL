<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");

 $CONFIGUREDIR = "/Applications/MAMP/Library/bin/mysql"; //location of mysql dump
 
     $dir = scandir('dbbackups', SCANDIR_SORT_DESCENDING);
     $newest_file = $dir[0];
     $file = ("dbbackups/".$newest_file);
     echo $file . " ";
    exec("$CONFIGUREDIR  --host=localhost -uroot -proot -hlocalhost formassess < ".$file, $outtext);

   print_r($outtext);
     exit;
           

?>