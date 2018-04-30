 <?php 
 // this file simply queries the database to populate the tables.
 
    require_once("../dbconfig.php");
   if(isset($_POST['type'])){

    echo getAccounts($_POST['type']);
   }
   if(isset($_POST['ID'])){
   
    echo deleteAccount($_POST['ID']);
    }


function getAccounts($type){
  $string = "";
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
   
       
        // query to read admins
        $sql = "SELECT * FROM accounts WHERE type = '$type' AND ID <> 0";
        $result = $pdo->query($sql);
        
         while($row = $result->fetch(PDO::FETCH_ASSOC) ){
          $name= $row['name'];
          $email = $row['email'];
          $ID = $row['ID'];
          $string .= "<tr><td style='display:none;'> $ID</td>
         <td> $name </td>  
          <td>$email</td>
           <td><button class='btn btn-sm deleteRowAdmin'>Delete</button></td></tr>" ; // html stuff

         }
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
           return $string;

          } 
     
        function deleteAccount($id){

// Sql query to delete the terms from database.
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);
   
    // prepare sql and bind parameters
    // example query. updates if an update has happend INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE name="A", age=19
    $sql = $pdo->prepare("DELETE FROM accounts
    WHERE ID = :id");
     $sql->bindParam(':id', $id);
    $sql->execute();
     
    echo "Deleted Item Successfully";
    }
catch(PDOException $e)
    {
     $error =  $e->getCode();
  if($error == 23000){
    echo "Cannot delete Account. This data is dependant in other tables ";

  }else echo "Error". $e->getMessage();
    }
$pdo = null;

        }
     ?>