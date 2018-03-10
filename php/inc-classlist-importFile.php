<?php
// This file imports CSV file into database
require_once("../dbconfig.php");



if(isset($_FILES["InputFile"]["name"])&& isset($_POST['listChoice'])) // check to see if file is being uploaded
{   
    $classNum = $_POST['listChoice'];
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
        $pdo = newPDO();
        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) // retrive data from csv
        {

            if($flag){
                $flag = false; // for checking if first line has titles in it
                if(strcmp($data[0],'Class Name')== 0){ // check if correct Ttiles
                $htmlString .= "<thead><tr>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[2]. "</th>
                <th>" .$data[3]. "</th>
                <th>" .$data[4]. "</th>
                <th>" .$data[5]. "</th>
                <th> Student or Teacher Exists Already</th>
              </tr></thead>  <tbody id = 't_body'>" ;
                }
                else {  // replace with correct Titles.
                    $htmlString .= "<thead><tr>
                    <th> Name </th>
                    <th> Email </th>
                    <th> Password </th>
                    <th> Class Name </th>
                    <th> Grade Level </th>
                    <th> School ID </th>
                    <th> Student or Teacher Exists Already</th>
                    <th> Already in Class List</th>
                  </tr></thead>  <tbody id = 't_body'>
                  ";

                  // check db fo first one
                  $htmlString .= "<tr class='text-danger'>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[2]. "</th>
                <th>" .$data[3]. "</th>
                <th>" .$data[4]. "</th>
                <th>" .$data[5]. "</th>
                <th> CSV Format Error: First Row Not Saved </th> </tr>";
                }
            }else {
                
                // for createing string from csv files. This is to srt the data
                $htmlString .= "<tr>
                <th>" .$data[0]. "</th>
                <th>" .$data[1]. "</th>
                <th>" .$data[2]. "</th>
                <th>" .$data[3]. "</th>
                <th>" .$data[4]. "</th>
                <th>" .$data[5]. "</th>";
     
           // query to see if data exists in accounts
           $query = ("SELECT ID FROM accounts WHERE name =:name and email = :email");
         $list = array(0 => ":name",1  => ":email");
         $data = array(0 => $data[0],1=>data[1]);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         $sql->execute();
         $row = $sql->fetch(PDO::FETCH_ASSOC) ;
         $accountID = $row['ID'];
           if(!$row) { // no data exists then insert
            if(strcmp($data[6],'True')== 0){
                $password = $data[6];
            } else {$password = password_hash(trim($data[6]," "), PASSWORD_DEFAULT);}
            $query =("INSERT INTO accounts (name, email, password)
            Values (:name, :email, :password)");
           $list = array(0 => ":name",1  => ":email",2 =>":password");
           $data = array(0 => $name,1=>$email,2=> $password);           
          $sql = pdo_preparedStmt($pdo,$query,$list,$data);
          
            // if school  not in table already then leave exists? columns blanck
            $htmlString .= "<th> &nbsp </th>";   
                
           }
            else{ // if data exists then update
                $htmlString .= "<th> &#10003 </th>"; 
            }
              // query to see if data exists in classes
           $query = ("SELECT * FROM classList WHERE accountID = :accountID AND classID = :classID");
           $list = array(0 => ":accountId", ":classID");
           $data = array(0=> $accountID ,1 => $classNum);           
          $success = pdo_preparedStmt($pdo,$query,$list,$data);
           $sql->execute();
           $row = $sql->fetch(PDO::FETCH_ASSOC) ;
             if(!$row) { // no data exists then insert
              $query =("INSERT INTO classList (accountID, classID)
              Values (:accountID, :classID)");
             $list = array(0 => ":accountId", ":classID");
             $data = array(0=> $accountID ,1 => $classNum);            
            $sql = pdo_preparedStmt($pdo,$query,$list,$data);
            
              // if   not in classlist  table already then leave exists? columns blanck
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
        echo pdo_error($e);
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


// creat new pd object
function newPDO(){
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

    return $pdo;

}
// preapared statement for insertins updates deletions.
// input: pdo object  output: sql 
function pdo_preparedStmt($pdo,$query,$parmList,$parmData){
$sql = $pdo->prepare($query);
for ($i=0; $i < count($parmList); $i++) { 
    $sql->bindParam($parmList[$i], $parmData[$i]);
}

return $sql->execute();
}

// for select queryies
// Takes PDO object and sql query and returns the resuling data not prepared
function pdo_query($pdo, $query)
{
    $result = $pdo->query($query);
    
    return $result;
}

function pdo_error($message){

    $error =  $message->getCode();
    return "Error". $message;}
      


    ?>

