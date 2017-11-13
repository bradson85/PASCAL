<?php
    include ('inc.functions.php');
    $assessID = $studentID = $results = "";
    if(isset($_POST['student']))
    {
        $studentID = $_POST['student'];
        $assessID = $_POST['id'];
        $results = $_POST['results']; 
        echo $results;
        $results = json_decode($results, true);
        //echo $results[0]['id'];
        print_r($results);
        submitResults($results, $studentID, $assessID);
    }
    else if(isset($_POST['id']))
    {
        $assessID = $_POST['id'];
        $assessID = clean_data($assessID);

        $pdo = pdo_construct();

        $sql = "SELECT t.ID, t.name, t.definition, a.isMatch, c.level "
        ."FROM assessmentquestions AS a, terms AS t, categories AS c " 
        ."WHERE a.assessmentID = $assessID AND t.ID = a.termID AND c.ID = t.catID ORDER BY c.level";
        // Execute the query, and return the tuples.
        $data = pdo_query($pdo, $sql);
        $pdo = null;
        echo json_encode($data->fetchAll());
    }

    function submitResults($results, $studentID, $assessID){
        $termID = $correct = "not changed";
        $pdo = pdo_construct();

        $sql = "INSERT INTO results (studentID, assessmentID, termID, correct) VALUES ";
        foreach($results as $value){
            $termID = $value['id'];
            $correct = $value['correct'];
            $sql .= "($studentID, $assessID, $termID, $correct), ";
        }
        
        $sql = substr($sql, 0, -2);
        $sql .= ";";
        echo $sql;
        pdo_exec($pdo, $sql);
        echo $sql;
        echo "got em?";
        $pdo = null;
    }
?>