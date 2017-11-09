<?php
    include ('inc.functions.php');
    $assessID = $studentID = "";
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $assessID = $_POST['id'];
        $studentID = $_POST['student'];
        $studentID = clean_data($studentID);
        $assessID = clean_data($assessID);

        $pdo = pdo_construct();

        $sql = "SELECT t.ID, t.name, t.definition, a.isMatch, c.level "
        ."FROM assessmentquestions AS a, terms AS t, categories AS c " 
        ."WHERE a.assessmentID = $assessID AND t.ID = a.termID AND c.ID = t.catID ORDER BY c.level";
        // Execute the query, and return the tuples.
        $data = pdo_query($pdo, $sql);

        echo json_encode($data->fetchAll());
    }
?>