 <?php 
    require_once("../db/dbconfig.php");
   $categories;
 
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        
        $sql = 'SELECT * FROM Categories';
        $result = $pdo->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC) ){
              array_push($categories,$row);
        }
        print_r($categories);
        // query
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
       /* $sql = 'SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
        categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID';
        $result = $pdo->query($sql);
         while($row = $result->fetch(PDO::FETCH_ASSOC) ){
          $ '<tr><td>'. $row['ID'] .'</td>'.
           '<td contenteditable= "true">'
           .'<select class="form-control" id="selcat">'
          . '</select></td>' 
         . '<td contenteditable= "true">'. $row['name'] .'</td>'  // term name
           . '<td contenteditable= "true">'. $row['definition'].'</td>'
           .'<td contenteditable= "true">'        /// for level
           .'<select class="form-control" id="sellev">'
           . '</select></td>' 
           . '<td><button class="btn btn-sm deleteRow">Delete</button></td></tr>' ;

         }*/
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
        
       
     
        
     ?>