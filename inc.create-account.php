<?php

    require_once('dbconfig.php');

    $name = $email = $accountType = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $accountType = $_POST['accountType'];
        echo "$accountType";
        clean_data($name);
        clean_data($email);

            // Echo return window location
        if(insert_to_db($name, $email, $accountType)){

        }
            echo '<script type="text/javascript">window.location = "create-account.php"</script>';
    }


    function clean_data($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        
        return $data;
    }

    function insert_to_db($name, $email, $accountType){
        // do db processing
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'before admins sql build';
        if(strcmp($accountType, "Administrator") == 0){
            $sql = "INSERT INTO admins (name, email) VALUES ('$name', '$email')";
            echo 'Ready to insert into admins';
        }
           

        elseif(strcmp($accountType, "Teacher") == 0){
            $sql = "INSERT INTO teachers (name, email, classID, schoolID) VALUES ('$name', '$email', 1, 1)";
        }
            
        elseif(strcmp($accountType, "Student") == 0) {
            $sql = "INSERT INTO students (classID, schoolID) VALUES (1, 1)";
        }
            
        $pdo->exec($sql);
            
    }

    function schoolOptions(){
        $pdo = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PWD);
        $sql = "SELECT * FROM schools";
        $result = $pdo->query($sql);

        while($row = $result->fetch()){
            $name = $row['name'];
            echo "<option>$name</option>";
        }

        echo "<option>Test</option>";
    }

    return;

?>