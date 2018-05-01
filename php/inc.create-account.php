<?php

    include ('inc.functions.php');

    $name = $email = $accountType = $returnMsg = "";
    //Insert form to database after checking textbox data
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $school = $_POST['school'];
        $class = $_POST['class'];
        $accountType = $_POST['accountType'];
        //echo "$accountType";
        //echo "$school";

        // clean any user inputted data
        clean_data($name);
        clean_data($email);
        $returnMsg = "true";
        if($name !== null || $email !== null || $name !== "" || $email !== "")
            if(!insert_to_db($name, $email, $accountType, $school, $class)){
                $returnMsg = "false";
            }
        else{
            $returnMsg = "true";

        }

        echo $returnMsg;
    }

    // inserts items to database
    function insert_to_db($name, $email, $accountType, $school, $class)
    {
        $type = -1;
        // do db processing
        if($name == "" || $name == null || ($email == "") || ($email == null) || $accountType == "")
           return false;
        // validate email using php validation
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
        // gets the class ID
        $pdo = pdo_construct();
        $sql = "SELECT classes.ID FROM classes, schools WHERE classes.name = '$class' AND schools.ID = classes.schoolID AND schools.name = '$school'";
        $result = $pdo->query($sql);

        $row = $result->fetch();
        $classID = $row['ID'];
        //echo "$classID";
        $pdo = null;
        $pdo = pdo_construct();

        //Compare strings because each account type has a separate table
        
        if(strcmp($accountType, "Administrator") == 0)
        {
            $type = 0;
            $sql = "INSERT INTO accounts (name, email, type) VALUES ('$name', '$email', $type)";
            //echo 'Ready to insert into admins';
        }
        elseif(strcmp($accountType, "Teacher") == 0)
        {
            $type = 1;
            $sql = "INSERT INTO accounts (name, email, type) VALUES ('$name', '$email', $type)";
        }   
        elseif(strcmp($accountType, "Student") == 0) 
        {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $type = 2;
            $sql = "INSERT INTO accounts (name, email, password, type) VALUES ('$name', '$email', '$password', $type)"; 
        }
        
        $pdo->exec($sql);
        $accountID = $pdo->lastInsertId();
        $pdo = null;

        $pdo = pdo_construct();
        $sql = "INSERT INTO classlist (classID, accountID) VALUES ($classID, $accountID)";

        // set up the email and database insertion to create a password.
        // once an account is created, a unique random ID is generated
        // and stored in database. when user clicks link in email, they can set up password
        if($type < 2 || ($email != "" || $email != null))
        {
            $guid = uniqid();
            $guid = hash("md5", $guid);

            $pdo = null;
            $pdo = pdo_construct();
            $date = date('Y-m-d h:m:s');
            //echo ("\n" . $date . "\n");
            $sql = "INSERT INTO password_change_requests (ID, time, userID) VALUES ('$guid', '$date', '$email')";

            $result = pdo_exec($pdo, $sql);

            // sends email to user
            if($result)
            {
                $to = $email;
                $subject = "Create Your Password";
                $txt = "You are recieving this message because the administrator has created you an account. Click here to complete your account set up: http://cafaprojectcs425.perado.tech/create-password.php?id=$guid";
                $headers = "FROM: Formative Assessment <s002722@siue.edu>";

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
    }
    return;

?>