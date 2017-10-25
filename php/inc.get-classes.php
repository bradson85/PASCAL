<?php
    include ('inc.functions.php');
    // Takes the selected item from the school select in create-account.php and returns classes associated with selected school
    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
        GetClasses($_POST);

    function GetClasses($postData)
    {
            $data = $postData['school'];
            if($data == null || $data == "" || $data == "Select a School")
                return false;
            $sql = "SELECT classes.name FROM classes, schools WHERE schools.id = classes.schoolID AND schools.name='{$data}'";
            $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
            $result = $pdo->query($sql);
            $resultArr = array();
            while($row = $result->fetch()){
                array_push($resultArr, $row['name']);
            }
            
                exit(json_encode($resultArr));
    }
    
    
?>