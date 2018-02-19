<?php
    include 'inc.functions.php';

    if(isset($_POST['classID']))
    {
        getStudents($_POST['classID']);
    }
    else if(isset($_POST['catID']))
    {
        getIDs($_POST['catID']);
    }
    else if((isset($_POST['students'])))
    {
        submitStudents($_POST['students']);
    }


    function getStudents($classID)
    {
        $pdo = pdo_construct();
        $sql = "SELECT a.ID, a.name FROM classlist AS c, accounts AS a WHERE a.ID = c.accountID AND c.classID = $classID";
        $result = pdo_query($pdo, $sql);

        echo json_encode($result->fetchAll());
    }

    function getIDs($catID) 
    {
        $pdo = pdo_construct();
        $sql = "SELECT c2.ID, c2.name, c2.level FROM categories AS c1, categories AS c2 WHERE (c2.level < (c1.level + 2)) AND (c2.level > (c1.level - 2)) AND c1.ID = $catID AND c2.name = c1.name ORDER BY c2.level";
        $result = pdo_query($pdo, $sql);

        echo json_encode($result->fetchAll());
    }

    function submitStudents($students)
    {
        $students = json_decode($students, true);

        $pdo = pdo_construct();
        $sql = $pdo->prepare("INSERT INTO assessmentassignments (assessmentID, studentID) VALUES (?, ?)");
        $sql->bindParam(1, $assessID);
        $sql->bindParam(2, $studentID);

        foreach($students as $value)
        {
            $assessID = $value['assessmentID'];
            $studentID = $value['studentID'];
            $sql->execute();
        }
        $pdo = null;
        echo "Success";
    }

?>