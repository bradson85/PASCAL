<?php
    if(!isset($_SESSION['email']))
        session_start();
    include ('inc.functions.php');
    $assessID = $email = $results = "";
    // check for different post values to determine what is being sent.
    if(isset($_POST['id']) && isset($_POST['results']))
    {
        $email = $_SESSION['email'];
        $assessID = $_POST['id'];
        $results = $_POST['results']; 
        echo $results;
        $results = json_decode($results, true);
        //echo $results[0]['id'];
        print_r($results);
        submitResults($results, $email, $assessID);
    }
    else if(isset($_POST['id']))
    {
        $assessID = $_POST['id'];
        $assessID = clean_data($assessID);
        $currDate = date('Y-m-d H:i:s', strtotime($_POST['date']));

        $pdo = pdo_construct();
        $sql = "SELECT start_date FROM assessments WHERE ID = $assessID";
        $result = pdo_query($pdo, $sql);
        $data = $result->fetch();
        $pdo = null;
        
        $pdo = pdo_construct();
        $email = $_SESSION['email'];
        $sql = "SELECT count(r.ID) FROM results AS r, accounts AS s  WHERE assessmentID = $assessID AND s.email = '$email' AND s.ID = r.studentID";
        $result = pdo_query($pdo, $sql);
        $result = $result->fetch();

        $assessDate = date('Y-m-d H:i:s', strtotime($data['start_date']));
        if($currDate < $assessDate || $result[0] > 0)
        {
            echo json_encode(array());
        }

        else
        {
            $pdo = pdo_construct();

            $sql = "SELECT t.ID, t.name, t.definition, a.isMatch, c.level "
            ."FROM assessmentquestions AS a, terms AS t, categories AS c " 
            ."WHERE a.assessmentID = $assessID AND t.ID = a.termID AND c.ID = t.catID ORDER BY c.level";
            // Execute the query, and return the tuples.
            $data = pdo_query($pdo, $sql);
            $pdo = null;
            echo json_encode($data->fetchAll());
        }
        
    }
    else if(isset($_POST['getLevel']))
    {
        $email = $_SESSION['email'];
        $pdo = pdo_construct();

        $sql = "SELECT c.gradeLevel FROM classes as c, accounts as s, classlist as cl WHERE cl.classID = c.ID AND s.ID = cl.accountID AND s.email = '$email'";
        $data = pdo_query($pdo, $sql);
        $pdo = null;
        echo json_encode($data->fetchAll());

    }
    // submits the results of the assessment to the server
    function submitResults($results, $email, $assessID){
        $termID = $matchID =  $correct = "";
        $pdo = pdo_construct();
        $sql = "SELECT ID from accounts AS a WHERE email = '$email' AND type = 2";
        $result = pdo_query($pdo, $sql);
        $studentID = $result->fetch();
        echo $studentID['ID'];
        $pdo = null;
        $pdo = pdo_construct();
        $sql = $pdo->prepare("INSERT INTO results (studentID, assessmentID, termID, matchID, correct) VALUES (?, ?, ?, ?, ?)");
        $sql->bindParam(1, $studentID['ID']);
        $sql->bindParam(2, $assessID);
        $sql->bindParam(3, $termID);
        $sql->bindParam(4, $matchID);
        $sql->bindParam(5, $correct);
        foreach($results as $value){
            $termID = $value['id'];
            $matchID = $value['mID'];
            $correct = $value['correct'];
            $sql->execute();
        }
        $pdo = null;
    }
?>