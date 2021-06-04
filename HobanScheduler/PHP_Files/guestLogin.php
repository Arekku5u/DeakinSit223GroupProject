<!doctype html>
<html>
    <head>
        <?php
            include "header.php";
            $_SESSION["location"] = "guestLogin.php";
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <link href="../Styles/style.css" rel="stylesheet" type="text/css">
        <link href="../Styles/login.css" rel="stylesheet" type="text/css">
    </head>

    <body class="main_body">
        <header class="main_header">
            <div class="topnav">
                <a href="index.php">Event Scheduler</a>
                <a class="active" href="login.php">Login</a>
                <a href="register.html">Register</a>

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
                <input type="text" name="fname" id="Uname" placeholder="First name">
                <br><br>
                <input type="text" name="lname" id="Uname" placeholder="Last name">
                <br><br>
                <input type="text" name="username" id="Uname" placeholder="Email">
                <br><br>
                <input type="hidden" name="run" value="TRUE">
                <input type="submit" name="login" id="log" value="Log In">
                <br><br>
                <span>Your details will only be stored for an hour, or until you log out. Details will only be saved if you join an event.</span>
                <br><br>
                <a class="register" href="login.php">Login with account</a>
            </form>

            <?php
                $fname = @$_POST["fname"];
                $lname = @$_POST["lname"];
                $username = @$_POST["username"];
                $run = @$_POST["run"];

                
                if ($run) {
                    if (empty($fname) || empty($lname) || empty($username)) {
                        print "<p>Please enter your username and password.</p>";
                    } else {
                        $loggedIn = TRUE;
                        setcookie("loggedIn", $loggedIn, time() + 3600); // create loggedIn cookie with value TRUE, expiring after 1 hour
                        $_SESSION["user"] = $username;
                        $_SESSION["fname"] = "(Guest) " . $fname;
                        $_SESSION["lname"] = $lname;
                        $_SESSION["role"] = "student";

                        header("refresh:0");
                    }
                }
            ?>
        </div>
    </body>
</html>