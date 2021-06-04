<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register an account</title>
    <link href="../Styles/style.css" rel="stylesheet" type="text/css">
    <link href="../Styles/register.css" rel="stylesheet" type="text/css">
</head>

<body class="main_body">
<header class="main_header">
    <div class="topnav">
        <a href="index.php">Event Scheduler</a>
        <a href="login.php">Login</a>
        <a class="active" href="register.php">Register</a>
    </div>
</header>


<!--<script>
    function myFunction() {
        var x = document.createElement("INPUT");
        x.setAttribute("type", "hidden");
        document.body.appendChild(x);

        document.getElementById("demo").innerHTML = "The Hidden Input Field was created. However, you are not able to see it because it is hidden (not visible).";
    }
</script>-->

<div class="register">
    <form id="register" method="post" action="register.php">
        <label><b>First Name</b></label>
        <br>
        <input type="text" name="Fname" id="Fname" placeholder="First Name">
        <br>
        <br>
        <label><b>Last Name</b></label>
        <br>
        <input type="text" name="Lname" id="Lname" placeholder="Last Name">
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
        <input type="checkbox" id="Check" name="Check" value="No" onclick="showfield(this)">
        <label for="Check"> Organiser</label><br>
        <br>
        <div id="selectOther"></div>
        <br>
        <input type="hidden" name="run" value="TRUE">
        <input type="submit" name="log" id="log" value="Register Account">

        <script type="text/javascript">
            function showfield(name){ // display the input box to select other (custom) member rol
                if (name.checked == true) document.getElementById('selectOther').innerHTML = '<label><b>Organiser ID </b> </label><input type="type" name="Code" id="Code" placeholder="ID" maxlength="64" pattern=".{2,64}" title="Minimum of two letters.">';
                else document.getElementById('selectOther').innerHTML = '';
            }
        </script>
    </form>



    <?php


    $fname = @$_POST["Fname"];
    $lname = @$_POST["Lname"];
    $email = @$_POST["Email"];
    $password = @$_POST["Pass"];
    $cpass = @$_POST["CPass"];
    $check = @$_POST["Check"];
    $code = @$_POST["Code"];
    $role = "student";
    $run = @$_POST["run"];
    $accounts_file = "../Data_Files/accounts.csv";

    $fnameErr = "";
    $lnameErr = "";
    $emailErr = "";
    $passErr = "";
    $cpassErr = "";
    $codeErr = "";

    $i = 0;
    $valid = true;

    $csv_fname = 1;
    $csv_lname = 2;
    $csv_email = 3;
    $csv_pass = 4;
    $csv_role = 5;


    function trim_value(&$value)
    {
        $value = rtrim($value);
    }

    function test_input($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }


    if ($run) {

        //Some validation I s̶t̶o̶l̶e̶ found
            if (empty($_POST["Fname"])) {
                $fnameErr = "First name is required";
                $valid = false;
            } else {
                $fnametest = test_input($_POST["Fname"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/",$fnametest)) {
                    $fnameErr = "Only letters and white space allowed";
                    $valid = false;
                }
            }

            if (empty($_POST["Lname"])) {
                $lnameErr = "Last name is required";
                $valid = false;
            } else {
                $lnametest = test_input($_POST["Lname"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/",$lnametest)) {
                    $lnameErr = "Only letters and white space allowed";
                    $valid = false;
                }
            }

            if (empty($_POST["Email"])) {
                $emailErr = "Email is required";
                $valid = false;
            } else {
                $emailtest = test_input($_POST["Email"]);
                // check if e-mail address is well-formed
                if (!filter_var($emailtest, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                    $valid = false;
                }
            }

            if (empty($_POST["Pass"])) {
                $passErr = "Password is required";
                $valid = false;
            }

            if (empty($_POST["CPass"]) || ($_POST["Pass"] !== $_POST["CPass"])) {
                $cpassErr = "Passwords must match";
                $valid = false;
            }

/*            print(isset($check));

            print("<br><br>");
        
            print(empty($_POST["Code"]));*/

            if (0 && 1) {
                print("test true");
            }
        
            print("<p>$check hi</p>");
            print("<p>$code hi</p>");

            print(isset($check) . " check, ");
            print(empty($code) . " empty code");

            if (isset($check) && (empty($code))) {
                $codeErr = "Please enter a valid organiser ID";
                $valid = false;
            }

            if ($valid == true) {

                if (is_readable($accounts_file)) {


                    $lines = explode("\n", file_get_contents('../Data_Files/accounts.csv'));
                    $headers = str_getcsv(array_shift($lines));
                    $data = array();
                    /* Grabs all the shit from the csv file and parts it into keys and values, the keys are the headers,
                    and the values are the pieces of data afterwards */

                    foreach ($lines as $line) {

                        $row = array();

                        foreach (str_getcsv($line) as $key => $field)
                            $row[$headers[$key]] = $field;

                        $row = array_filter($row);

                        $data[] = $row;

                    }

                    // Separates all the emails of the $data array from earlier.
                    $emails = array();
                    $array_rows = count($data);
                    for ($i = 0; $i < count($data); $i++) {
                        array_push($emails, $data[$i]['email']);
                    }

                    // No idea Mason did it ;)
                    $counter = file($accounts_file);
                    $id = count($counter);

                    // Organiser ID hard coded for the sake of demonstration

                    if (isset($check)){
                        $role = "organiser";
                    }

                    // Checks if the email provided by the page is contained the $emails array.
                    if (in_array($email, $emails)) {
                        echo "<br>";
                        echo "Email already in use. Try again!";
                    } else {
                        // Some voodoo magic to fix the newline issue. :D
                        $handle = fopen($accounts_file, "a+");
                        $list = $id . "," . $fname . "," . $lname . "," . $email . "," . $password . "," . $role;

                        fputs($handle, "\n");
                        fputs($handle, $list);

                    }


                }
            }
            else{
                print"<br>$fnameErr <br> $lnameErr <br> $emailErr <br> $passErr <br> $cpassErr <br> $codeErr";
            }
    }
    ?>
</div>
</body>
</html>

<!-- 

--TO DO LIST--

1. [CHECK] Work out how to write to the end of a CSV, rather than replace it [CHECK]

2. [CHECK] Find the length of the CSV and add 1 to get the ID [CHECK]

3. [CHECK] Check if email is already in use [CHECK]

4. [CHECK] Name validation [CHECK]

5. [CHECK] Email validation [CHECK]

6. [CHECK] Password validation [CHECK]

7. Add role selection (Radio button?)
        7.1. If organiser is selected ask for ID or code?

8. Once account is created, user is logged in

 -->