<?php
    include('inc-createassessment-getTerms.php');
    require_once('../dbconfig.php');

    if(isset($_POST['catID']) && isset($_POST['classID']) && isset($_POST['startDate']))
    {
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $catID = $_POST['catID'];
        $startDate = $_POST['startDate'];
        $classID = $_POST['classID'];

        $sql = "INSERT INTO assessments (start_date, catID, classID) VALUES ($startDate, $catID, $classID)";
        $pdo->exec($sql);
        $last_id = $pdo->lastInsertId();

        echo $last_id;
    }
    else if(isset($_POST['catID']))
    {
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $catID = $_POST['catID'];

        $sql = "SELECT * FROM terms WHERE catID = $catID";

        $result = $pdo->query($sql);

        echo json_encode($result->fetchAll());

    }
    else if(isset($_POST['assessData']))
    {
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $array = json_decode($_POST['assessData'], true);
        echo ($array[0]['termID']);
        $sql = "INSERT INTO assessmentquestions (termID, assessmentID, isMatch) VALUES";
        $termID = $isMatch = $assessmentID = "";
        foreach($array as $value){
            $termID = $value['termID'];
            $isMatch = $value['isMatch'];
            $assessmentID = $value['assessmentID'];
            $sql .= "($termID, $assessmentID, $isMatch), ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= ";";
        echo $sql;
        $pdo->exec($sql);


    }

?>
