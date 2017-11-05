<?php
// this a file full of two functions that do some quering internally

require_once("../dbconfig.php");

if (isset($_POST['function'])) {
   $sel= array();
   $ID = json_decode(stripslashes($_POST['IDs']),true);
   foreach ($ID as $key => $value) {
   array_push($sel,selected($value["ID"])); 
   }
  
   echo json_encode($sel);
   
}

// this takes the unique key of a category and level and returns the cateogory id
function matchCatID($categoryName, $level){
    
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

 $sql = $pdo->prepare("SELECT ID From categories WHERE name = :catName AND level = :level");

 $sql->bindParam(':catName', $catName, PDO::PARAM_INT);
 $sql->bindParam(':level', $lev);
     $catName = $categoryName;
     $lev = $level;
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
          $value= $row["ID"];     

}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
return $value;
}

//this calculates which category is selcted for the select option.
function selected($ID){
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

        $sql = $pdo->prepare("SELECT catID From terms WHERE ID = :ID");
        $sql->bindParam(':ID', $ID,PDO::PARAM_INT);
        $sql->execute();
        $catID = $sql->fetch(PDO::FETCH_ASSOC) ;
        
        $sql = $pdo->prepare("SELECT categories.name, categories.level From categories WHERE ID = :catID");
        $sql->bindParam(':catID', $catID['catID'],PDO::PARAM_INT);
        $sql->execute();
      while(  $row = $sql->fetch(PDO::FETCH_ASSOC)){
            $level = $row['level'];
              $catName = $row['name'];
         
      }
        $pdo = null;
            
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
         return ($catName. " ". $level);
}
?>