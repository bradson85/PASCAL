<?php
    $page_title = "Create Password";
    $css_path = "css/main.css";

    include('php/inc.header.php');
    include('php/inc.create-password.php');
?>

    <body>
        <div class="container">
            <h1>Create/Change Password</h1>
            
            <form action="php/inc.create-password.php" method="POST" id="createPassword">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm" placeholder="Confirm Password">
                </div>
    
                <button id="submit" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        
    </body>