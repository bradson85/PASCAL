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
        // $sql = "SELECT catID, classID FROM assessments WHERE assessments.ID = $assessID";
        // //asdf
        // $result = pdo_query($pdo, $sql);
        // $row = $result->fetch();

        // $catID = $row['catID'];

        // $pdo = null;
        // $pdo = pdo_construct();
        // $sql = "SELECT * FROM terms WHERE terms.catID = $catID";
        $sql = "SELECT t.ID, t.name, t.definition, a.isMatch FROM assessmentquestions AS a, terms AS t WHERE a.assessmentID = $assessID AND t.ID = a.termID";
        // Execute the query, and return the tuples.
        $data = pdo_query($pdo, $sql);

        echo json_encode($data->fetchAll());
    }
?>