<?php
    @session_start();
    @session_regenerate_id();
    @ob_start();

    $loggedIn = @$_COOKIE["loggedIn"];
    $user = @$_SESSION["user"];
    $fname = @$_SESSION["fname"];
    $role = @$_SESSION["role"];

    // logs a user out if they are not 'logged in' but username is still defined (occurs when loggedIn cookie expires)
    if ($loggedIn != TRUE && !empty($user)) {
        setcookie("loggedIn", "", time() - 1);
        $_SESSION["user"] = "";
        $_SESSION["role"] = "";
        header("refresh:0");
    }
?>