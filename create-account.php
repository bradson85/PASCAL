<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Account</title>
        <link href="css/main.css" rel="stylesheet">
    </head>

    <body>
        <div id="CreateAccount">
            <h1>Create Account</h1>
            <form action="" method="POST" class="form">
                <div><p>Account Type:</p>
                    <select id="accountType" name="accountType" required>
                        <option>Select Account Type...</option>
                        <option>Administrator</option>
                        <option>Teacher</option>
                        <option>Student</option>
                    </select>
                </div>

                <div><p>Name:</p> <input type="text" id="name" name="name" required>
                </div>

                <div><p>Username:</p> <input type="text" id="username" name="username">
                </div>

                <div><p>Email:</p> <input type="email" id="email" name="email" required>
                </div>

                <div> <button id="submit" type="submit">Submit</button> </div>

            </form>
        </div>
    </body>

</html>