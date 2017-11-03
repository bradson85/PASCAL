<?php
    include ('inc.functions.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $assessID = $_POST['id'];
        $studentID = $_POST['student'];

        $pdo = pdo_construct();
        $sql = "SELECT catID, classID FROM assessments WHERE assessment.ID = $id";
        $result = pdo_query($pdo, $sql);

        $pdo = null;
        $pdo = pdo_construct();
        $sql = "SELECT terms.name, terms.definition FROM terms, categories WHERE categories.name = '$catName' AND categories.ID = terms.catID";

        // Execute the query, and return the tuples.
        $data = pdo_query($pdo, $sql);
        echo $data;
    }
?>