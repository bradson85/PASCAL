<?php
   require_once("../dbconfig.php");


   function getTerms($catID){

    $echostring = "";
   // Sql query to selct all the terms from database where catid is same.
   try {
       $pdo = new PDO(DB_CONNECTION_STRING,
       DB_USER, DB_PWD);
       $pdo->setAttribute(PDO::ATTR_ERRMODE,
       PDO::ERRMODE_EXCEPTION);

       // prepare sql and bind parameters
       // example query. updates if an update has happend INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
       $sql = $pdo->prepare("SELECT * FROM terms
       WHERE catID = :ID");
       $value= $catID;
       $sql->bindParam(':ID', $value);
       $sql->execute();
       while(  $row = $sql->fetch(PDO::FETCH_ASSOC)){
        $word = $row['name'];
        $def = $row['definition'];
      $id = $row['ID'];
      $echostring.= ("<tr><td td contenteditable= 'true'>$word</td>
     <td contenteditable= 'true'> $def</td>
      </tr>");

       }

  $pdo = null;
       }
   catch(PDOException $e)
       {
       echo "Error: " . $e->getMessage();
       }
      echo $echostring;
    }
   ?>
