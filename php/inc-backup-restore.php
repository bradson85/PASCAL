<?php
 require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");

 $CONFIGUREDIR = "/Applications/MAMP/Library/bin/mysql"; //location of mysql dump
 
     $dir = scandir('dbbackups', SCANDIR_SORT_DESCENDING);
     $newest_file = $dir[0];
     $file = ("dbbackups/".$newest_file);
     echo $file . " ";
     echo $string = "$CONFIGUREDIR  --host=".DB_HOST." -u".DB_USER." -p".DB_PWD." ".DB_NAME." < ".$file;
    exec($string, $outtext);

   print_r($outtext);
     exit;
           

?>