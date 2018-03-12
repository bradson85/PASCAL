<?php
// This file imports CSV file into database
require_once("../dbconfig.php");

if(isset($_FILES["InputFile"]["name"])) // check to see if file is being uploaded
{
    $filename= $_FILES["InputFile"]["name"];
    $ext=substr($filename,strrpos($filename,"."),(strlen($filename)-strrpos($filename,".")));

    $errorflag = false;
    $htmlString = ("<H4>Imported Data:</H4><small class= 'text-info'>If format looks off check your csv file is properly formatted.</small>
    <div class='table-responsive'>
    <small class= 'text-secondary'>If necessary scroll down go see all imported data</small>
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
                $flag = false; // for checking if first line has titles in it
                if(strcmp($data[0],'Class Name')== 0){ // check if correct Ttiles
                $htmlString .= "<thead><tr>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[2]. "</th>
                <th> School Exists Already</th>
              </tr></thead>  <tbody id = 't_body'>" ;
                }
                else {  // replace with correct Titles.
                    $htmlString .= "<thead><tr>
                    <th> Class Name </th>
                    <th> Grade Level </th>
                    <th> School ID </th>
                    <th> School Exists Already</th>
                  </tr></thead>  <tbody id = 't_body'>
                  ";

                  // check db fo first one
                  $htmlString .= "<tr class='text-danger'>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[2]. "</th>
                <th> CSV Format Error: First Row Not Saved </th> </tr>";
                }
            }else {
                
                // for createing string from csv files. This is to srt the data
                $htmlString .= "<tr>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[2]. "</th>";
     
           // query to see if data exists
     $sql = $pdo->prepare("SELECT * From classes WHERE name = :name AND gradeLevel = :gradeLevel 
                            AND schoolID = :schoolID");
     $sql->bindParam(':name', $name);
     $sql->bindParam(':gradeLevel', $gradeLevel);
     $sql->bindParam(':schoolID', $schoolID);
     $name = trim($data[0]," ");
     $gradeLevel = trim($data[1]," ");
     $schoolID =trim($data[2]," ");
         $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
           if(!$row) { // no data exists then insert
           
            $sql = $pdo->prepare("INSERT IGNORE INTO classes (name, gradeLevel, schoolID)
             Values (:name, :gradeLevel, :schoolID)");
            $sql->bindParam(':name', $name);
            $sql->bindParam(':gradeLevel', $gradeLevel);
            $sql->bindParam(':schoolID', $schoolID);
            $name = trim($data[0]," ");
            $gradeLevel = trim($data[1]," ");
            $schoolID = trim($data[2]," ");
           $sql->execute();
          
            // if school  not in table already then leave exists? columns blanck
            $htmlString .= "<th> &nbsp </th>";   
                
           }
            else{ // if data exists then update
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







