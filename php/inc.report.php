<?php
    include ("inc.functions.php");

    if(isset($_GET['school']) && !isset($_GET['class']))
    {
        getClasses($_GET['school']);
    }
    else if(isset($_GET['class']))
    {
        getResults($_GET['class']);
    }
    else if($_SERVER['REQUEST_METHOD'] == "GET")
    {
        getSchools();
    }

    function getClasses($school)
    {
        $pdo = pdo_construct();
        $sql = "SELECT c.name FROM classes AS c, schools AS s WHERE s.name = '$school' AND s.ID = c.schoolID";
        $data = pdo_query($pdo, $sql);
        $data = $data->fetchAll();
        $pdo = null;
        echo json_encode($data);
    }

    function getSchools()
    {
        $pdo = pdo_construct();
        $sql = "SELECT name FROM schools";
        $data = pdo_query($pdo, $sql);
        $data = $data->fetchAll();
        $pdo = null;
        echo json_encode($data);
    }

    function getResults($class)
    {
        $pdo = pdo_construct();
        $sql = "SELECT s.ID, count(r.correct) AS correct, a.ID AS assessID, a.start_date FROM students AS s, classes AS c, results AS r, assessments AS a WHERE c.ID = s.classID AND c.name = '$class' AND r.assessmentID = a.ID AND r.correct = 1 AND s.ID = r.studentID GROUP BY s.ID, a.ID ORDER BY s.ID";
        $data = pdo_query($pdo, $sql);
        $data = $data->fetchAll();
        $pdo = null;

        echo json_encode($data);
    }
?>