<?php
// This file imports CSV file into database
require_once("../dbconfig.php");

if(isset($_FILES["InputFile"]["name"]) && isset($_POST['classNum'])) // check to see if file is being uploaded
{
    $classNum = $_POST['classNum'];
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

        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) // retrive data from csv
        {
            if($flag){
                $flag = false; // for checking if first line has titles in it
                if(strcmp($data[0],'Name')== 0){ // check if correct Ttiles
                $htmlString .= startResponseString($data);
                }
                else {  // replace with correct Titles.
                    $htmlString .= correctedResponseString($data);
                }
            }else {
                $htmlString .= responseString($data);
           // query to see if data exists in accounts
          
         $result = checkAccountID($data);
         $accountID =$result[1];
           if($result[0]) { // no data exists then insert
            if(strcmp($data[6],'True')== 0){
                $password = $data[6];
            } else {$password = password_hash(trim($data[6]," "), PASSWORD_DEFAULT);}
               insertAccounts($data);
          
            // if school  not in table already then leave exists? columns blanck
            $htmlString .= "<th> &nbsp </th>";   
                
           }
            else{ // if data exists then update
                $htmlString .= "<th> &#10003 </th>"; 
            }
              //see if data exists in classes
               
             if(checkClasses($classNum, $accountID)) { // no data exists then insert
                insertIntoClasslist($classNum,$accountID);
            
              // if   not in classlist  table already then leave exists? columns blanck
              $htmlString .= "<th> &nbsp </th>";   
                  
             }
              else{ // if data exists then update
                  $htmlString .= "<th> &#10003 </th>"; 
              }
        }
        $htmlString .= "</tr>";
     }
    
    
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
} else {echo "Error";}


// creat new pd object
function newPDO(){
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

    return $pdo;

}
// preapared statement for insertins updates deletions.
// input: pdo object  output: sql succsess or not
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
    return "Error". $error." ".$message;}


function startResponseString($data){

    $string .= "<thead><tr>
    <th>" .trim($data[0]," "). "</th>
    <th>" .trim($data[1]," "). "</th>
    <th>" .trim($data[2]," "). "</th>
    <th>" .trim($data[3]," "). "</th>
     <th>" .trim($data[4]," "). "</th>
    <th>" .trim($data[5]," "). "</th>
    <th> " .trim($data[7]," "). "</th>
    <th> Student or Teacher Exists Already</th>
    <th> Already in Class List</th>
  </tr></thead>  <tbody id = 't_body'>" ;

  return $string;
    }


function correctedResponseString($data){

    $string .= "<thead><tr>
    <th> Name </th>
    <th> Email </th>
    <th> Password </th>
    <th> Class Name </th>
    <th> Grade Level </th>
    <th> School ID </th>
    <th> Teacher</th>
    <th> Student or Teacher Exists Already</th>
    <th> Already in Class List</th>
  </tr></thead>  <tbody id = 't_body'>
  ";

  $string.= "<tr class='text-danger'>
  <th>" .trim($data[0]," "). "</th>
    <th>" .trim($data[1]," "). "</th>
    <th>" .trim($data[2]," "). "</th>
    <th>" .trim($data[3]," "). "</th>
     <th>" .trim($data[4]," "). "</th>
    <th>" .trim($data[5]," "). "</th>
    <th> " .trim($data[7]," "). "</th>
<th> CSV Format Error: First Row Not Saved </th>
<th> &nbsp </th> </tr>";
  return $string;
    }

    function responseString($data){

     // for createing string from csv files. This is to srt the data
     $string .= "<tr>
     <th>" .trim($data[0]," "). "</th>
     <th>" .trim($data[1]," "). "</th>
     <th>" .trim($data[2]," "). "</th>
     <th>" .trim($data[3]," "). "</th>
      <th>" .trim($data[4]," "). "</th>
     <th>" .trim($data[5]," "). "</th>
     <th> " .trim($data[7]," "). "</th>";

     return $string;
    }


function getSchoolIDFromSchoolName($school){
    try {
        $pdo = newPDO();
        $query = ("SELECT ID FROM schools WHERE name = '$school'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $id = $row["ID"];
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $id;

}

//returns ture if item doesnt exists and return s account ID
function checkAccountID($data){
    $exists = false;
   try {
    $pdo = newPDO();
    $query = ("SELECT ID FROM accounts WHERE name='" .$data[0]."' and email ='".$data[1]."'");
    $result = pdo_query($pdo,$query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if(!$row){
       $exists = true;
       $row['ID'] = 0; // if account doesnt exist then there is no $row["ID"]
       }
      }  catch(PDOException $e)
         {
          echo pdo_error($e);
         }
      $pdo = null;
      return array(0=>$exists,1=>$row['ID']);
}

//returns ture if item doesnt exists
function checkClasses($classNum, $accountID){
    $exists = false;
    try {
    $pdo = newPDO();
    $query = ("SELECT * FROM classlist WHERE accountID ='$accountID' AND classID ='$classNum'");
    $result = pdo_query($pdo,$query);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    if(!$row){
        $exists = true;
    }
      }  catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
      return $exists;
}

function insertIntoClasslist($classNum,$accountID){
    try {
    $pdo = newPDO();
    $query =("INSERT INTO classlist (classID, accountID)
    Values (:classID, :accountID)");
   $list = array(0=>":classID", 1=>":accountID");
   $result = array(0=>$classNum ,1=>$accountID);            
  $sql = pdo_preparedStmt($pdo,$query,$list,$result);
      }  catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
}

function insertAccounts($data){
    try {
    $pdo = newPDO();
    $query =("INSERT IGNORE INTO accounts (name, email, password ,type)
    Values (:name, :email, :password, :type)");
   $list = array(0=>":name",1=>":email",2=>":password",3=>":type");
   if(checkForTeacher($data[7])){
    $result = array(0=>$data[0],1=>$data[1],2=>$data[2],3=>1); // account type is 1 for teacher 
  }else {
   $result = array(0=>$data[0],1=>$data[1],2=>$data[2],3=>2); // account type is 2 for student 
  }          
  $sql = pdo_preparedStmt($pdo,$query,$list,$result);
      }  catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
}

function checkForTeacher($info){

    $exists = false;
    if(strcmp($info,"True")==0 || strcmp($info,"true")==0 ){

        $exists = true;
    }
  return $exists;
} 
    ?>

