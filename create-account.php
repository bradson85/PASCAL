<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Account</title>
        <link href="css/main.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    </head>

    <body>
        <div class="container" id="CreateAccount">
            <h1>Create Account</h1>
            <form action="" method="POST" class="form">
                <div class="form-group"><label for="accountType">Account Type:</label>
                    <select id="accountType" class="form-control" name="accountType" required>
                        <option>Select Account Type</option>
                        <option>Administrator</option>
                        <option>Teacher</option>
                        <option>Student</option>
                    </select>
                </div>

                <div class="form-group"><label for="name">Name:</label> <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group"><label for="username">Username:</label> <input type="text" class="form-control" id="username" name="username">
                </div>

                <div class="form-group"><label for="email">Email:</label> <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <button id="submit" type="submit" class="btn btn-primary">Submit</button>

            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    </body>

</html>