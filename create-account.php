<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Account</title>
        <link href="css/main.css" rel="stylesheet">
    </head>

    <body>
        <h1>Create Account</h1>
        <form action="" method="POST">
            <div>Account Type: 
                <select id="accountType" name="accountType" required>
                    <option>Administrator</option>
                    <option>Teacher</option>
                    <option>Student</option>
                </select>
            </div>

            <div>Name: <input type="text" id="name" name="name" required>
            </div>

            <div>Username: <input type="text" id="username" name="username">
            </div>

            <div>Email: <input type="email" id="email" name="email" required>
            </div>

            <div><button id="submit" type="submit">Submit</button> </div>

        </form>
    </body>

</html>