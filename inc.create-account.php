<?php
    $name = $username = $email = $accountType = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $accountType = $_POST['accountType'];

        clean_data($name);
        clean_data($username);
        clean_data($email);
        clean_data($accountType);

        if(insert_to_db($name, $username, $email, $accountType))
            // Echo return window location
            echo '<script type="text/javascript">window.location = "create-account.php"</script>';
    }


    function clean_data($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        
        return $data;
    }

    function insert_to_db($name, $username, $email, $accountType){
        // do db processing

        return true;
    }

?>