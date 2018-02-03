 <?php 
 // this file simply queries the database to populate the tables.
 // it runs one query for the drop down menu then another for the rest of the table.
    require_once("../dbconfig.php");
   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // collect value of input field
      $data = $_POST['data'];
      if( strcmp($data, "All")== 0){
        $categories = getCategories();
       getallwords( $categories);
      }else{
      $level = substr($data, -1);
      $category =substr($data, 0, -2);
      $categories = getCategories();
      getsomewords($category,$level,$categories);
  }
}
function getCategories(){
   $string ="";
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // query to get all categries for drop down menu
        $sql = "SELECT * FROM categories";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
              $catName = $row['name'];
              $lev = $row['level'];    
              $string .= "<option value = \"$catName $lev\"> $catName - Level $lev</option>"; //html     
        }
      
      $pdo = null;
      } catch (PDOException $e) {
      die( $e->getMessage() );
      } 
        return $string;
      }   
function getsomewords($category,$level,$input){
      try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        // query for the rest of the table
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
        $sql =  $sql = $pdo->prepare('SELECT terms.ID, terms.name, terms.definition, categories.name AS "category",
        categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
         WHERE categories.name = :name AND categories.level =:level ');
        $sql->bindParam(':name', $category);
        $sql->bindParam(':level', $level);
        $sql->execute();
        
         while($row = $sql->fetch(PDO::FETCH_ASSOC) ){
          $word= $row['name'];
          $level = $row['level'];
          $def = $row['definition'];
          $ID = $row['ID'];
          $cat= $row['category'];
          echo "<tr><td style='display:none;'>$ID</td><td>
           <select class='form-control' id='selcat'><<option value = \"0\"> --Select Category/Level--</option> $input
          </select></td> 
         <td contenteditable= 'true'> $word </td>  
          <td contenteditable= 'true'>$def</td>
           <td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>" ; // html stuff

         }
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
        
          }
     function getallwords($input){
      try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        // query for the rest of the table
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
        $sql =  $sql = 'SELECT terms.ID, terms.name, terms.definition, categories.name AS "category",
        categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID';
       $result = $pdo->query($sql);
        
         while($row = $result->fetch(PDO::FETCH_ASSOC) ){
          $word= $row['name'];
          $level = $row['level'];
          $def = $row['definition'];
          $ID = $row['ID'];
          $cat= $row['category'];
          echo "<tr><td style='display:none;'>$ID</td><td>
           <select class='form-control' id='selcat'><<option value = \"0\"> --Select Category/Level--</option> $input
          </select></td> 
         <td contenteditable= 'true'> $word </td>  
          <td contenteditable= 'true'>$def</td>
           <td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>" ; // html stuff

         }
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 

     }
        
     ?>