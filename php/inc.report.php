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
        $sql = "SELECT r.assessmentID, r.studentID, a.start_date, count(r.correct) AS correct "
        ."FROM results AS r, assessments AS a, classes AS c, students AS s "
        ."WHERE r.correct > 0 AND c.name = '$class' AND a.ID = r.assessmentID AND s.ID = r.studentID"
        ."GROUP BY r.studentID";
        $data = pdo_query($pdo, $sql);
        $data = $data->fetchAll();
        $pdo = null;

        echo json_encode($data);
    }
?>