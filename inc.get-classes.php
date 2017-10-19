<?php
    require_once('dbconfig.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = $_POST['school'];

        $sql = "SELECT classes.name FROM classes, schools WHERE schools.id = classes.schoolID AND schools.name='{$data}'";
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $result = $pdo->query($sql);
        $resultArr = array();
        while($row = $result->fetch()){
            array_push($resultArr, $row['name']);
        }

        exit(json_encode($resultArr));
    }
?>