 <?php 
 // this file simply queries the database to populate the tables.
 
 require_once("../dbconfig.php");
 
  try {
      $pdo = new PDO(DB_CONNECTION_STRING,
      DB_USER, DB_PWD);
      $pdo->setAttribute(PDO::ATTR_ERRMODE,
      PDO::ERRMODE_EXCEPTION);
 
     
      // query to read admins
      $sql = 'SELECT students.ID, classes.name AS className FROM students INNER JOIN classes ON classes.ID = students.classID';
      $result = $pdo->query($sql);
      
       while($row = $result->fetch(PDO::FETCH_ASSOC) ){
        $class = $row['className'];
        $ID = $row['ID'];
        echo "<tr><td style='display:none;'> $ID</td>  
        <td>$class</td>
         <td><button class='btn btn-sm deleteRowStudent'>Delete</button></td></tr>" ; // html stuff

       }
          $pdo = null;
          } catch (PDOException $e) {
          die( $e->getMessage() );
          } 
     
        
     ?>