<?php
    $page_title = "Create Password";
    $css_path = "css/main.css";

    include('php/inc.header.php');
    include('php/inc.create-password.php');
    // Get the ID from the URL and pass it to the getUser function to be processed and find the userID
    $id = $_GET['id'];
    $user = getUser($id);

    echo $user;
?>

    <body>
        <div class="container">
            <h1>Create/Change Password</h1>
            
            <form action="php/inc.create-password.php" method="POST" id="createPassword">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Confirm Password">
                </div>
    
                <button id="submit" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        
    </body>