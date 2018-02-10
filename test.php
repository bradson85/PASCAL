<?php  
require_once("dbconfig.php");
$username = "admin@test.com";
try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT * FROM accounts";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
            $hash = $row['password'];
            echo strlen($hash);
            if (password_verify('test', $hash)){ echo "true";}else echo "false";
             if(($username == $row['email'] || $username == $row['name']) && password_verify("test" , $row['password'])){
              echo '<p> worked </p>';
                   
		break;
             }    
            }    
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
     

      
      ?>