<?php
require_once($_SERVER['DOCUMENT_ROOT']."/dbconfig.php");



if(isset($_POST['categoriesSel'])){ // get sorting box for terms
    getSearchBox();
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
    if($error == 23000){
      return "Cannot delete Category. Terms Exist in the database 
      that depend on this category. Delete all the Terms associated with
      this category first. ";
  
    }else {return "Error". $message;}
      }



// delete item from table
function deleteFromTable($table, $where,$data){
    try {
       $pdo = newPDO();
       $query = ("DELETE FROM $table WHERE $where = :$where");
        $list = array(0 => ":$where");
        $newdata = array(0 => $data); 
       $success = pdo_preparedStmt($pdo,$query,$list,$newdata);
         if($success){
         echo "Deleted Item Successfully";
         }
         else{ echo "Deletion of Data ID:$data Failed";}
         }
     catch(PDOException $e)
         {
         echo pdo_error($e);
         }
     $pdo = null;

}

function retrieveSelectedCatLevel($id){

    
        try {
            $pdo = newPDO();
            $query = ("SELECT level FROM categories WHERE ID = '$id'");
            $result = pdo_query($pdo,$query);
             
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $level = $row["level"];
              }
          catch(PDOException $e)
              {
              echo pdo_error($e);
              }
          $pdo = null;
     return $level;
}

function retrieveSelectedClassLevel($id){

        try {
            $pdo = newPDO();
            $query = ("SELECT gradeLevel FROM classes WHERE ID = '$id'");
            $result = pdo_query($pdo,$query);
             
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $level = $row["gradeLevel"];
              }
          catch(PDOException $e)
              {
              echo pdo_error($e);
              }
          $pdo = null;
     return $level;
}

function retrieveSelectedSchool($id){

    try {
        $pdo = newPDO();
        $query = ("SELECT name FROM schools WHERE ID = '$id'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $school = $row["name"];
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $school;


}


function checkIfIDExists($type,$what){
    $exists = false;
    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM $type WHERE ID = '$what'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            $exists=false;
        }
        else $exists = true;
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $exists;
    
    }

    function checkCustom($type,$where,$what){
        $exists = false;
        try {
            $pdo = newPDO();
            $query = ("SELECT * FROM $type WHERE $where = \"$what\"");
            $result = pdo_query($pdo,$query);
             
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if(!$row){
                $exists=false;
            }
            else $exists = true;
              }
          catch(PDOException $e)
              {
              echo pdo_error($e);
              }
          $pdo = null;
     return $exists;

    }

    function checkIfInfoExists($type,$where,$what,$id){
        $exists = false;
        try {
            $pdo = newPDO();
            $query = ("SELECT * FROM $type WHERE $where =\"$what\" AND ID ='$id'");
            $result = pdo_query($pdo,$query);
             
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if(!$row){
                $exists=false;
            }
            else $exists = true;
              }
          catch(PDOException $e)
              {
              echo pdo_error($e);
              }
          $pdo = null;
     return $exists;
        
        }

function updateTerms($id, $term,$definition,$catID){
    try {
        $pdo = newPDO();
        $query = ("UPDATE IGNORE terms SET name =:word , definition = :definition,
                     catID = :catID WHERE ID = :IDs");
         $list = array(0 => ":word",1 => ":definition",2 => ":catID", 3 => ":IDs");
         $data = array(0 => $term,1 => $definition,2 => $catID, 3 => $id);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Changed info for $term successfully";
          }
          else{ return "Update of $term Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 
 }


function insertTermsIntoDB($term,$definition,$catID){

    try {
        $pdo = newPDO();
        $query = ("INSERT INTO terms (name, definition, catID)
        Values (:word , :definition, :catID)");
         $list = array(0 => ":word",1 => ":definition",2 => ":catID");
         $data = array(0 => $term, 1 => $definition,2 => $catID);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Added $term successfully";
          }
          else{ return "Adding $term Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;


}

function updateCategories($id, $catName,$level){
    try {
        $pdo = newPDO();
        $query = ("UPDATE IGNORE categories SET name =:catName , level = :level
                      WHERE ID = :IDs");
         $list = array(0 => ":catName",1 => ":level",2 => ":IDs");
         $data = array(0 => $catName,1 => $level,2 => $id);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Changed info for $catName level $level successfully";
          }
          else{ return "Update of $catName level $level Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 
 }


function insertCategoriesIntoDB($name,$level){

    try {
        $pdo = newPDO();
        $query = ("INSERT INTO categories (name, level)
        Values (:name , :level)");
         $list = array(0 => ":name",1 => ":level");
         $data = array(0 => $name, 1 => $level);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Added $name level $level successfully";
          }
          else{ return "Adding $name level $level Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;


}

function updateSchools($id, $name){
    try {
        $pdo = newPDO();
        $query = ("UPDATE IGNORE schools SET name =:name
                      WHERE ID = :IDs");
         $list = array(0 => ":name",1 =>":IDs");
         $data = array(0 => $name,1 => $id);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Changed info for $name  successfully";
          }
          else{ return "Update of $name  Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 
 }



function insertSchoolIntoDB($name){
    
    try {
        $pdo = newPDO();
        $query = ("INSERT INTO schools (name)
        Values (:name)");
         $list = array(0 => ":name");
         $data = array(0 => $name);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Added $name successfully";
          }
          else{ return "Adding $name  Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;


}

function updateClasses($id, $name,$level,$schoolID){
    try {
        $pdo = newPDO();
        $query = ("UPDATE IGNORE classes SET name = :name , gradeLevel = :gradeLevel, schoolID= :schoolID
                      WHERE ID = :IDs");
         $list = array(0 => ":name",1 => ":gradeLevel",2=> ":schoolID",3 => ":IDs");
         $data = array(0 => $name, 1 => $level, 2 => $schoolID,3 => $id);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Changed info for $name successfully";
          }
          else{ return "Update of $name Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 
 }

function insertClassIntoDB($name,$level,$schoolID){
    try {
        $pdo = newPDO();
        $query = ("INSERT INTO classes (name, gradeLevel, schoolID)
        Values (:name , :gradeLevel, :schoolID)");
         $list = array(0 => ":name",1 => ":gradeLevel",2=> ":schoolID");
         $data = array(0 => $name, 1 => $level, 2 => $schoolID);           
        $success = pdo_preparedStmt($pdo,$query,$list,$data);
         
          if($success){
          return "Added $name successfully";
          }
          else{ return "Adding $name  Failed";}
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;


}

function getSchoolIDFromClasses($id){


    try {
        $pdo = newPDO();
        $query = ("SELECT schoolID FROM classes WHERE ID = '$id'");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $id = $row["schoolID"];
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $id;

}

function retrieveSelectedCategory($id){

    
        try {
            $pdo = newPDO();
            $query = ("SELECT name,level FROM categories WHERE ID = '$id'");
            $result = pdo_query($pdo,$query);
             
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $level = $row['level'];
            $catName = $row['name'];
              }
          catch(PDOException $e)
              {
              echo pdo_error($e);
              }
          $pdo = null;
    return ($catName. " ". $level);
}


// returns associatve array list of categories
function  getCategoriesData(){
 try {
       $pdo = newPDO();
       $query = ("SELECT * FROM categories");
       $result = pdo_query($pdo,$query);
        
       $row = $result->fetchAll(PDO::FETCH_ASSOC);
         }
     catch(PDOException $e)
         {
         return pdo_error($e);
         }
     $pdo = null;
return $row;
}

function getTermsData(){

    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM terms");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;
 }

 function getTermsDataSpecial($categoryID){

    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM terms WHERE catID = \"$categoryID\"");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;
 }
 /// select what data you get customized
 // this is trcky  don't connect this to user input data
 function customQuery($table,$what,$where,$data){
    try {
        $pdo = newPDO();
        $query = ("SELECT $what FROM $table
        WHERE $where = '$data'");
        $result = pdo_query($pdo,$query);;
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
         
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;
 }


 function getSchoolData(){

    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM schools");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;
 }

 function getClassData(){

    try {
        $pdo = newPDO();
        $query = ("SELECT * FROM classes");
        $result = pdo_query($pdo,$query);
         
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
          }
      catch(PDOException $e)
          {
          echo pdo_error($e);
          }
      $pdo = null;
 return $row;
 }

 
 function matchCatID($categoryName, $level){
    try {
        $pdo = newPDO();
        $query =("SELECT ID From categories WHERE name = '$categoryName' AND level = '$level'");
       $sql = pdo_query($pdo, $query);
     
         $row = $sql->fetch(PDO::FETCH_ASSOC);  
         $id = $row['ID'];   
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
    return $id;
    }
function getCatIDFromTermID($termID){
    try {
        $pdo = newPDO();
        $query =("SELECT catID From terms WHERE ID = '$termID'");
       $sql = pdo_query($pdo, $query);
     
         $row = $sql->fetch(PDO::FETCH_ASSOC); 
         $id = $row['catID']; 
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
    return $id;
    }

    // input school name find school ID;
function matchSchoolName($name){
    try {
        $pdo = newPDO();
        $query =("SELECT ID From schools WHERE name = \"$name\"");
       $sql = pdo_query($pdo, $query);
     
         $row = $sql->fetch(PDO::FETCH_ASSOC);  
         $id = $row['ID'];   
    }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
    $pdo = null;
    return $id;
    }

    function getSearchBox(){

        $selectString = "<td><select class=\"form-control\" id=\"sort\"><<option value=\"All\" selected>All Words</option>";
        
        try {
            $pdo = newPDO();
        // query all the categories
        $query = ("SELECT * FROM categories");
        
        $result = pdo_query($pdo,$query);
         while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
               $catID = $row['ID'];
               $catName = $row['name'];
               $level = $row['level']; 
        // more of the html placing the variables inside
        //   . here for concatination you concat with . not + in php;
               $selectString.= "<option value = \"$catName $level\"> $catName - Level $level</option>";
        
         }
        }
        catch(PDOException $e)
            {
                echo pdo_error($e);
            }
        $pdo = null;
        // close the html brackets
        $selectString.= "</select></td>";
        echo $selectString; // return string.
        }


?>