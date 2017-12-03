 <?php 
  // this file simply queries the database to populate the tables.
 
 require_once("../dbconfig.php");
 
  try {
      $pdo = new PDO(DB_CONNECTION_STRING,
      DB_USER, DB_PWD);
      $pdo->setAttribute(PDO::ATTR_ERRMODE,
      PDO::ERRMODE_EXCEPTION);
 
     
      // query to read admins
      $sql = 'SELECT * FROM teachers';
      $result = $pdo->query($sql);
      
       while($row = $result->fetch(PDO::FETCH_ASSOC) ){
        $name= $row['name'];
        $email = $row['email'];
        $ID = $row['ID'];
        echo "<tr><td style='display:none;'> $ID</td>
       <td> $name </td>  
        <td>$email</td>
         <td><button class='btn btn-sm deleteRowTeacher'>Delete</button></td></tr>" ; // html stuff

       }
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
     
        
     ?>