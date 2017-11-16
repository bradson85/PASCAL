
<?php
// this a file full of two functions that do some quering internally

require_once("../db/dbconfig.php");


// this takes the unique key of a category and level and returns the cateogory id
function matchIDToCat($ID){

    $string="";
try {
    $pdo = new PDO(DB_CONNECTION_STRING,
    DB_USER, DB_PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,
    PDO::ERRMODE_EXCEPTION);

 $sql = $pdo->prepare("SELECT name, level From categories WHERE ID = :ID");

 $sql->bindParam(':ID',$ID);
     
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC) ;
        $string.= $row["name"] ."," . $row['level'];     

}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
    }
$pdo = null;
return $string;
}

?>