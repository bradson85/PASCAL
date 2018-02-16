<?php
    include 'inc.functions.php';

    if(isset($_POST['classID']))
    {
        getStudents($_POST['classID']);
    }


    function getStudents($classID)
    {
        $pdo = pdo_construct();
        $sql = "SELECT a.ID, a.name FROM classlist AS c, accounts AS a WHERE a.ID = c.accountID AND c.classID = $classID";

        $result = pdo_query($pdo, $sql);

        echo json_encode($result->fetchAll());
    }

?>