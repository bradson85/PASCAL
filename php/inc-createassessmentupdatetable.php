<?php 
   require_once("../db/dbconfig.php");
   
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // collect value of input field
       $data = $_POST['data'];
   }
   
   // Sql query to selct all the terms from database where catid is same.
   try {
       $pdo = new PDO(DB_CONNECTION_STRING,
       DB_USER, DB_PWD);
       $pdo->setAttribute(PDO::ATTR_ERRMODE,
       PDO::ERRMODE_EXCEPTION);
       $html = "";
       // prepare sql and bind parameters
       // example query. updates if an update has happend INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
       $sql = $pdo->prepare("SELECT * FROM terms
       WHERE catID = :catID");
        $sql->bindParam(':catID', $catID);
       $catID = $data;
       $sql->execute();
       $count = 1;
       while(  $row = $sql->fetch(PDO::FETCH_ASSOC)){
        $word = $row['name'];
        $def = $row['definition'];    
      $id = $row['ID'];       

    $html = $html . ("<tr><td><input type=\"checkbox\" class=\"form-check-input check\" value=\"$id\"></td>
   <td>$word</td>  
    <td>$def</td>
    </tr>");


    
  }
  echo $html;
  $pdo = null;  
       }
   catch(PDOException $e)
       {
       echo "Error: " . $e->getMessage();
       }

   
   ?>