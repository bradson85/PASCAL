 <?php 
    require_once("../db/dbconfig.php");
   
  
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    // get all categries for drop down.
        $sql = "SELECT * FROM categories";
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
              $catName = $row['name'];
              $lev = $row['level'];    
              $categories .= "<option value = \"$catName $lev\"> $catName - Level $lev</option>";      
        }
       
        // query
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
        $sql = 'SELECT terms.ID, terms.name, terms.definition, categories.name AS "category",
        categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID';
        $result = $pdo->query($sql);
        
         while($row = $result->fetch(PDO::FETCH_ASSOC) ){
          $word= $row['name'];
          $level = $row['level'];
          $def = $row['definition'];
          $ID = $row['ID'];
          $cat= $row['category'];
          echo "<tr><td> $ID</td> <td>
           <select class='form-control' id='selcat'><<option value = \"0\"> --Select Category/Level--</option> $categories
          </select></td> 
         <td contenteditable= 'true'> $word </td>  
          <td contenteditable= 'true'>$def</td>
           <td><button class='btn btn-sm deleteRow'>Delete</button></td></tr>" ;

         }
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
        
       
     
        
     ?>