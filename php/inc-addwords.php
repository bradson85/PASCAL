 <?php 
    require_once("../db/dbconfig.php");
    include "wordsclass.php";

    try {
        $pdo = new PDO(DB_CONNECTION_STRING,
        DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
        categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID';
        $result = $pdo->query($sql);
        while ( $row = $result->fetch() ) {
            $words = new WordClass($row);
            echo $words->getID(). "-" .
                 $words->getWord(). "-" .
                 $words->getDefinition(). "-" .
                 $words->getCatName(). "-" .
                 $words->getLevel()."<br>";
            }
            $pdo = null;
            } catch (PDOException $e) {
            die( $e->getMessage() );
            } 
        
       // query
//SELECT terms.ID,terms.name, terms.definition, categories.name AS "category",
//categories.level FROM terms INNER JOIN categories ON terms.catID = categories.ID
        

        
     ?>