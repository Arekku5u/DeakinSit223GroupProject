<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register an account</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link href="register.css" rel="stylesheet" type="text/css">
    </head>

    <body class="main_body">
        <header class="main_header">
            <div class="topnav">
                <a href="index.php">Event Scheduler</a>
                <a href="login.php">Login</a>
                <a class="active" href="register.php">Register</a>
            </div>
        </header>

        <div class="register">
            <form id="register" method="post" action="register.php">
                <label><b>Name</b></label>
                <br>
                <input type="text" name="Uname" id="Uname" placeholder="Name">
                <br>
                <br>
                <label><b>Email Address</b></label>
                <br>
                <input type="text" name="Email" id="Email" placeholder="Email">
                <br>
                <br>
                <label><b>Password </b> </label>
                <input type="Password" name="Pass" id="Pass" placeholder="Password">
                <br>
                <br>
                <label><b>Confirm Password </b> </label>
                <input type="Password" name="CPass" id="CPass" placeholder="Password">
                <br>
                <br>
                <input type="hidden" name="run" value="TRUE">
                <input type="submit" name="log" id="log" value="Register Account">
            </form>

        <?php
            $name = @$_POST["Uname"];
            $email = @$_POST["Email"];
            $password = @$_POST["Pass"];
            $cpass = @$_POST["CPass"];
            $run = @$_POST["run"];
            $accounts_file = "accounts.csv";

            $csv_fname = 1;
            $csv_email = 3;
            $csv_pass = 4;
            $csv_role = 5;



            //This doesnt work, contsntantly says un and pw is empty
            if (empty($password) || empty($username) || empty($email)){
                print "<p>Please enter your username and password.</p>";
            } else {
                    print"<p>good</p>";
            }

            $list = array($name, $email, $password);

            if (is_readable($accounts_file)) {
                print"<p>accounts is readable</p>";

                $file = fopen($accounts_file, "w");
                fputcsv($file, $list, ",");
            } else {
                print"<p>accounts is unreadble</p>";
            }
            
            
                
        ?>
        </div>
    </body>
</html>
