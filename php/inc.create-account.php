<?php

    include ('inc.functions.php');

    $name = $email = $accountType = "";
    //Insert form to database after checking textbox data
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $school = $_POST['school'];
        $class = $_POST['class'];
        $accountType = $_POST['accountType'];
        echo "$accountType";
        echo "$school";
        clean_data($name);
        clean_data($email);
        
        if($name != null && $email != null && $name != "" && $email != "")
            if(!insert_to_db($name, $email, $accountType, $school, $class)){
                $errMsg = "Invalid Email)";
            }
        else{
            $errMsg = "Invalid input (Name or Email)";
        }
        // Echo return window location
        echo '<script type="text/javascript">window.location = "../create-account.php"</script>';
    }

    //inserts items to database
    function insert_to_db($name, $email, $accountType, $school, $class)
    {
        // do db processing
        if($name == "" || $name == null || ($email == "" && strcmp($accountType != "Student")) || ($email == null && strcmp($accountType != "Student")) || $accountType == "")
            return false;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) && strcmp($accountType != "Student"))
            return false;
        $pdo = pdo_construct();

        $sql = "SELECT classes.ID FROM classes, schools WHERE classes.name = '$class' AND schools.ID = classes.schoolID AND schools.name = '$school'";
        $result = $pdo->query($sql);

        $row = $result->fetch();
        $classID = $row['ID'];
        echo "$classID";
        $pdo = null;
        $pdo = pdo_construct();

        //Compare strings because each account type has a separate table
        if(strcmp($accountType, "Administrator") == 0)
        {
            $sql = "INSERT INTO admins (name, email) VALUES ('$name', '$email')";
            echo 'Ready to insert into admins';
        }
           

        elseif(strcmp($accountType, "Teacher") == 0)
        {
            $sql = "INSERT INTO teachers (name, email, classID) VALUES ('$name', '$email', $classID)";
        }
            
        elseif(strcmp($accountType, "Student") == 0) 
        {
            $sql = "INSERT INTO students (classID) VALUES ($classID)";
        }

        
            
        $pdo->exec($sql);
        $pdo = null;

        if($email != "" && $email != null)
        {
            $guid = uniqid();
            $guid = hash("md5", $guid);

            $pdo = null;
            $pdo = pdo_construct();
            $date = date('Y-m-d h:m:s');
            echo ("\n" . $date . "\n");
            $sql = "INSERT INTO password_change_requests (ID, time, userID) VALUES ('$guid', '$date', '$email')";

            $result = pdo_exec($pdo, $sql);

            if($result)
            {
                $to = "bpshelton01@gmail.com";
                $subject = "Create Your Password";
                $txt = "You are recieving this message because the administrator has created you an account. Click here to complete your account set up: localhost/form-assess/create-password.php?id=$guid";
                $headers = "FROM: Formative Assessment <formassess-no-reply@siue.edu>";

                mail($to, $subject, $txt, $headers);
            }
        }
        return true;
    }
    // Get school options and add to the school 'select'
    function schoolOptions()
    {
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