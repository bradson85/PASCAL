<?php
    $page_title = "Create Password";
    $css_path = "css/main.css";

    include('php/inc.header.php');
    include('php/inc.create-password.php');
    // Get the ID from the URL and pass it to the getUser function to be processed and find the userID
    $id = $_GET['id'];
    $user = getUser($id);
?>

    <body id="createPasswordPage">

    
    
        <div class="container">
        
            <h1>Create/Change Password</h1>
            <span id="alertPlaceholder"></span>
            <div class="card">
                <div class="card-body">
                    <form id="createPassword" novalidate method="POST">
                        <div class="form-group">
                            <input type="text" readonly class="form-control-plaintext" name="email" id="email" value="Email: <?php echo $user ?>">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <div class="invalid-feedback">
                                Field cannot be blank.
                            </div>
                        </div>
                        <div class="form-group">
                            
                            <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Confirm Password" required>
                            <div class="invalid-feedback">
                                Field cannot be blank.
                            </div>
                        </div>
            
                        <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        <script src="js/create-password.js"></script>
    </body>