<?php 
    require_once("../db/dbconfig.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // collect value of input field
        echo $id = $_POST['data'];
    }
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // get all categries for drop down.
        $sql = $pdo->prepare("SELECT * FROM terms WHERE catID = :data");

       $sql->bindParam(':data', $data);
       $data = $id;
        $sql->execute();
        while($row = $sql->fetch(PDO::FETCH_ASSOC) ){
              $word = $row['name'];
              $def = $row['definition'];    
            $id = $row['ID'];       
 
          echo ("<tr><td><input type='check' value='$id'></td>  
         <td contenteditable= 'true'> $word </td>  
          <td contenteditable= 'true'>$def</td>
           </tr>");
         }
        
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
        
       echo $string;
     
        
     ?>