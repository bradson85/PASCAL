<?php
    include 'inc.functions.php';

    if(isset($_SESSION['class']))
    {
        getStudents($_SESSION['class']);
    }
    else if(isset($_POST['catID']))
    {
        getIDs($_POST['catID']);
    }
    else if((isset($_POST['students'])))
    {
        submitStudents($_POST['students']);
    }
    else if(isset($_POST['ID']) && isset($_POST['value']) && isset($_POST['type']))
    {
        updateAssessment($_POST['ID'], $_POST['value'], $_POST['type']);
    }
    else if(isset($_POST['deleteID'])) 
    {
        deleteAssessment($_POST['deleteID']);
    }

    function updateAssessment($ID, $val, $type) 
    {
        $sql = "UPDATE assessments SET $type = '$val' WHERE ID = $ID";
        $pdo = pdo_construct();
        $result = pdo_exec($pdo, $sql);
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

    function deleteAssessment($delID) 
    {
        $sql = "DELETE FROM assessments WHERE ID = $delID";
        $pdo = pdo_construct();
        $resp = pdo_exec($pdo, $sql);

        echo $resp;
    }

?>