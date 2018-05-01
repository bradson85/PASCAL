<?php
    //testing variables
     $_SESSION['class'] = 1;
     $_SESSION['ID'] = 31;
     $_SESSION['type'] = 0;

    include('inc-createassessment-getTerms.php');
    require_once('../dbconfig.php');

    if(isset($_POST['catID']) && isset($_POST['startDate']))
    {
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $catID = $_POST['catID'];
        $startDate = $_POST['startDate'];
        $classID = $_SESSION['class'];
        $endDate = (strtotime($startDate));
        $endDate = strtotime("+7 day", $endDate);
        $endDate = date('Y-m-d H:i:s', $endDate);

        if($startDate == "" || $catID == 0)
        {
            echo "false";
            return;
        }
            


        $sql = "INSERT INTO assessments (start_date, end_date, catID) VALUES ('$startDate', '$endDate', $catID)";

        $pdo->exec($sql);
        $last_id = $pdo->lastInsertId();
        $pdo = null;
        echo $last_id;

        if($_SESSION['type'] == 0) {
            $assessmentID = $last_id;
            $accountID = $_SESSION['ID'];
            $sql = "INSERT INTO assessmentassignments (assessmentID, studentID) VALUES ($assessmentID, $accountID)";

            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
            $result = $pdo->exec($sql);
        }

        
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
        //echo ($array[0]['termID']);
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
        if($pdo->exec($sql)) {
            echo "Success";
        } 

        else {
            echo "Fail";
        }


    }

?>
