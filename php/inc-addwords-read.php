 <?php 
    require_once("../db/dbconfig.php");
   
 $json;
    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        // query
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
        $sql = 'SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
        categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID';
        $result = $pdo->query($sql);
         $row = $result->fetchAll(PDO::FETCH_ASSOC) ;
         $json=json_encode($row);
             
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
        
       
        
       echo $json;
        
     ?>