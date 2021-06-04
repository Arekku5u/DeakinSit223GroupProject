<!doctype html>
<html>
    <head>
        <?php
            include "header.php";
            $_SESSION["location"] = "login.php";
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link href="login.css" rel="stylesheet" type="text/css">
    </head>

    <body class="main_body">
        <header class="main_header">
            <div class="topnav">
                <a href="index.php">Event Scheduler</a>
                <a class="active" href="login.php">Login</a>
                <a href="register.php">Register</a>

                <?php
                    if ($loggedIn) {
                        print ('
                            <form method="post">
                                <input type="submit" name="logout" style="float:right; font-size: 14px; padding: 16px 18px" value="Log out">
                            </form>
                            <span style="float:right">Logged in as ' . $fname . '</span>'
                        );
                    } else {
                        print('<span style="float:right">Not logged in</span>');
                    }

                    if (@$_POST["logout"]) {
                        setcookie("loggedIn", "", time() - 1);
                        header("refresh:0");
                    }
                ?>
            </div>
        </header>

        <br>
        <div class="login">
            <form id="login" method="post">
                <input type="text" name="username" id="Uname" placeholder="Email">
                <br><br>
                <input type="password" name="password" id="Pass" placeholder="Password">
                <br><br>
                <input type="hidden" name="run" value="TRUE">
                <input type="submit" name="login" id="log" value="Log In">
                <br><br>
                <input type="checkbox" name="remember_me" id="check">
                <span>Remember me</span>
                <br><br>
                <a class="fgtpass" href="#">Forgot Password</a> <!-- Not implemented -->
                <a class="register" href="register.html">Register an Account</a><br><br>
                <a class="register" href="guestLogin.php">Login as Guest</a>
            </form>

            <?php
                $username = @$_POST["username"];
                $password = @$_POST["password"];
                $rememberMe = @$_POST["remember_me"];
                $run = @$_POST["run"];
                $accounts_file = "accounts.csv";

                $csv_fname = 1;
                $csv_email = 3;
                $csv_pass = 4;
                $csv_role = 5;

                // Check the email and password provided (if both of these values exist and the user is not already logged in)
                if ($run) {
                    if ($loggedIn) {
                        print "<p>Error: You are already logged in.</p>";
                    } else {
                    if (empty($password) || empty($username)) {
                        print "<p>Please enter your username and password.</p>";
                        } else {
                            if (is_readable($accounts_file)) {
                                $handle = fopen($accounts_file, "r");
                                $i = 0;
                                while ($row = fgetcsv($handle, 500)) {
                                    if ($row[$csv_email] == $username) {
                                        // print ("Username " . $username . " exists at ID " . $i . ", on line " . $i + 1);
                                        if ($row[$csv_pass] == $password) {
                                            $loggedIn = TRUE;
                                            print "<p>Logged in as " . $username . "!</p>";
                                            $fname = $row[$csv_fname];
                                            $role = $row[$csv_role];
                                            print($role);
                                        }
                                    }
                                    $i++;
                                }

                                if ($loggedIn != TRUE) {
                                    print "<p>Username or password incorrect.</p>";

                                // Log the user in
                                } else { 
                                    // set timeout based on 'remember me' selection: 3 days or 1 hour
                                    if ($rememberMe) {
                                        $timeout = 259200; // 3-day timeout
                                    } else {
                                        $timeout = 3600; // 1-hour timeout
                                    }
                                    
                                    // set cookie and session values to show an account is logged in and specify its details
                                    setcookie("loggedIn", $loggedIn, time() + $timeout); // create loggedIn cookie with value TRUE, and vary login timeout based on 'remember me' selection
                                    $_SESSION["user"] = $username;
                                    $_SESSION["fname"] = $fname;
                                    $_SESSION["role"] = $role;
                
                                    header("refresh:0");
                                }
                            }
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>