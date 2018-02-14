<?php
// This file imports CSV file into database
require_once("../dbconfig.php");
include "inc-addwords-functions.php";
if(isset($_FILES["InputFile"]["name"])) // check to see if file is being uploaded
{
   
    $filename= $_FILES["InputFile"]["name"];
    $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));

    $errorflag = false;
     $htmlString = ("<div class='table-responsive'>
     <H5>Imported Data:</H5>
     <table id='word_table' class='table table-striped table-bordered'>");

    //we check,file must be have csv extention
    if($ext==".csv")
    {
      $file = fopen($_FILES["InputFile"]["tmp_name"], "r"); // get it from temp folder
     $flag = true;
     
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) // retrive data from csv
        {
            if($flag){
                $flag = false; // for skipping first line with titles in it
                $htmlString .= "<thead><tr>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[3]. "</th>
                <th>" .$data[4]. "</th>
                <th> Category Exists </th>
                <th> Term Exists</th>
              </tr></thead>  <tbody id = 't_body'>";
            }else {
                
                // for createing string from csv files. This is to srt the data
                $htmlString .= "<tr>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[3]. "</th>
                <th>" .$data[4]. "</th>";
                

           // query to see if  category data exists
   $sql = $pdo->prepare("SELECT name, level FROM categories 
                            WHERE name = :category AND level = :gradeLevel");
     $sql->bindParam(':gradeLevel', $gradeLevel);
     $sql->bindParam  (':category', $category);
     $gradeLevel = trim($data[3]," ");
    $category = trim($data[4]," ");
     $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
           if(!$row) { // no data exists then insert
           
            $sql = $pdo->prepare("INSERT IGNORE INTO categories (name, level)
             Values (:name, :gradeLevel)");
            $sql->bindParam(':name', $category);
            $sql->bindParam(':gradeLevel', $gradeLevel);
            $gradeLevel = trim($data[3]," ");
            $category = trim($data[4]," ");
            $sql->execute();

            // if category not in table already then leave exists? columns blanck
            $htmlString .= "<th> &nbsp </th>";           
           } else{ // if data exists Mark so;     
            $htmlString .= "<th> &#10003 </th>";
            }


                    // query to see if data exists
     $sql = $pdo->prepare("SELECT definition FROM terms 
      WHERE definition = :def");
    $sql->bindParam(':def', $def);
    $def= trim($data[1]," ");
$sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC) ;
if(!$row) { // no data exists then insert

$sql = $pdo->prepare("INSERT IGNORE INTO terms (name, definition, catID)
Values (:name, :def, :catID)");
$sql->bindParam(':name', $term);
$sql->bindParam(':def', $def);
$sql->bindParam(':catID', $catID);
$catID = trim(matchCatID(trim($data[4]," "),trim($data[3]," ")),"");
                $term =  trim($data[0]," ");
                
$sql->execute();
 // if word not in table already then leave exists? columns blank
 $htmlString .= "<th> &nbsp </th>";

}
else{ // if data exists mark so
    $htmlString .= "<th> &#10003 </th>";
}
     }
     $htmlString .= "</tr>";
    }
    }
    catch(PDOException $e)
        {
            // use "fal" in get for fail 
        $error = "Error: " . $e->getMessage();
        $errorflag = true;
        }
    $pdo = null;

        fclose($file); // close file
         // this redirects back to add words page with a message in the get
         // use "imp" in get for succes
        $success = "CSV import success" ;
       
    }
    else {
         // this redirects back to add words page with a message in the get
         // use "fal" in get for fail 
        $error=  "Error: No csv found";
        $errorflag = true;
    }
        $htmlString .= "</tbody></table>";

    $arr = array( 0 => $errorflag,
    1 => $success,
    2 => $htmlString,
    3 => $error
    );

    
    echo json_encode($arr);
}
    ?>