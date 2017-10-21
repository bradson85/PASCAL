<?php

function displayHeader(){
	echo 'meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">';
}

function displayLogin(){
    
        $form = '<section>
                <form id="login-form" class="rounded" method="post" action="inc-login.php">
                    <p><input type="text" name="username" placeholder="username" id="login-username"/></p>
                    <p><input type="password" name="password" placeholder="password" id="login-password" /></p>
                    
                    <footer id="login-footer">
                        <span id="forgot-password"><a href="forgotPassword.php">Forgot password?</a></span>
                        <button id="cancel-button" name="cancel"><a href="../index.php">Cancel</a></button>
                        <button id="signIn-button" name="signIn"><a id="theButton" href="assess-summary.php">Sign in</a></button>
                    </footer>
                </form>
            </section>';
        
        echo $form;
    }

function displayFooter(){
	
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="js/login.js"></script>';
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    global $username, $password;
    $isAdmin = false;
	$isValid = true;
	$username = input($_POST["username"]);
	
	if(empty($username)){
		$isValid = false;
	} else if (!usernameValid($username)){
		$isValid = false;
	}
	
	$password = input($_POST["password"]);
	if(empty($password)){
		$isValid = false;
	} else if (!passwordValid($password)){
		$isValid = false;
	}
	
	if($isValid){
        if(strpos($username, 'admin') !== false){
            header('Location: ./admin-dashboard.php');
        } else {
            header('Location: ./teacher-dashboard.php');
        }
	} else if (!$isValid){
		header('Location: ./login.php');
	}
	
}
	
function usernameValid($username){
	return preg_match( '/^[a-zA-Z0-9]{5,}$/', $username);	
}

function passwordValid($password){
	return preg_match( '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/', $password);
}

function input($input){
	$input = trim($input);
	$input = stripcslashes($input);
	$input = htmlspecialchars($input);
	
	return $input;
}