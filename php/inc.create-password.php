<?php
    include ('inc.functions.php');

    $pwd = $confirmPwd = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $pwd = $_POST['password'];
        $confirmPwd = $_POST['confirm'];

        echo $pwd;
        echo " ". $confirmPwd;
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
            echo $pwd;
            // Insert to DB
            insert("teacher@il.edu", $pwd);
        }

        else
        {
            echo "Fields must match.";
        }
        
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
        if(checkType($email, "Teachers"))
            $type = "Teachers";
        else
        {
            $type = "Administrators";
        }
            

        $sql = "UPDATE $type SET password='$pwd' WHERE email='$email'";
        $pdo = pdo_construct();        
        if(pdo_exec($pdo, $sql))
            echo "Successfully updated password";
        else
            echo "Problem updating password";

    }

    function checkType($email, $type)
    {
        $sql = "SELECT ID FROM $type WHERE email='$email'";
        $pdo = pdo_construct();
        $result = pdo_query($pdo, $sql);
        $pdo = null;

        return($result->rowCount() > 0);
    }
?> 