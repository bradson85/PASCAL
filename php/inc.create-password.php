<?php
    include ('inc.functions.php');

    $email = $pwd = $confirmPwd = $errMsg = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $email = $_POST['email'];
        $email = substr($email, 7, (strlen($email)-1));
        $pwd = $_POST['password'];
        $confirmPwd = $_POST['confirm'];
        $errMsg = "true";
        //echo $pwd;
        //echo " ". $confirmPwd;
        // Check for null or empty values
        if($pwd === null || $pwd === "" || $confirmPwd === null || $confirmPwd === "")
        {
            $errMsg = "Fields cannot be left blank.";
        }
            
        // Check strings match
        
        else if($pwd === $confirmPwd)
        {
            // Salt and hash
            $pwd = password_hash($pwd, PASSWORD_DEFAULT);
            //echo $pwd;
            // Insert to DB
            insert($email, $pwd);
        }

        else
        {
            $errMsg = "Fields must match.";
        }

        echo $errMsg;
        
    }

    function getUser($id)
    {
        if($id == "" || $id == null)
            return null;
        
        $sql = "SELECT userID FROM password_change_requests WHERE ID='$id'";
        $pdo = pdo_construct();
        $result = pdo_query($pdo, $sql);
        $row = $result->fetch();

        return $row['userID'];
    }

    function insert($email, $pwd)
    {
        $sql = "UPDATE accounts SET password='$pwd' WHERE email='$email'";
        $pdo = pdo_construct();        
        if(pdo_exec($pdo, $sql))
            $msg = "Successfully updated password";
        else
            $msg = "Problem updating password";

    }

    function checkType($email, $type)
    {
        $sql = "SELECT ID FROM acou WHERE email='$email'";
        $pdo = pdo_construct();
        $result = pdo_query($pdo, $sql);
        $pdo = null;

        return($result->rowCount() > 0);
    }
?>