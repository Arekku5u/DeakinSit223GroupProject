<!doctype html>
<html>
<head>
    <?php
    include "header.php";
    $_SESSION["location"] = "index.php";
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<meta http-equiv="refresh" content="1; url='login.html'" />-->
    <title>Hoban Scheduler</title>
    <link href="../Styles/style.css" rel="stylesheet" type="text/css">
    <link href="../Styles/eventcolour.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.dhtmlx.com/scheduler/edge/dhtmlxscheduler.js"></script>
    <script src='../Scripts/dhtmlxscheduler_serialize.js'></script>
    <script src="../Scripts/dhtmlxscheduler_editors.js"></script>
    <link href="https://cdn.dhtmlx.com/scheduler/edge/dhtmlxscheduler_material.css"
          rel="stylesheet" type="text/css" charset="utf-8">
    <style>
        html, body {
            margin: 0px;
            padding: 0px;
            height: 100%;
            overflow: hidden;
        }

    </style>
</head>

<body class="main_body">
<header class="main_header">
    <div class="topnav">
        <a class="active" href="index.php">Event Scheduler</a>
        <a href="login.php">Login</a>
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
<div id="scheduler_here" class="dhx_cal_container" style="width:100%; height:100%;">
    <div class="dhx_cal_navline">
        <div class="dhx_cal_prev_button">&nbsp;</div>
        <div class="dhx_cal_next_button">&nbsp;</div>
        <div class="dhx_cal_today_button"></div>
        <div class="dhx_cal_date"></div>
        <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
        <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
        <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
    </div>
    <div class="dhx_cal_header"></div>
    <div class="dhx_cal_data"></div>
    <form id="json_form" action="json_writer.php" method="post" target="hidden_frame">
        <input type="hidden" name="data" value="" id="data">
    </form>
</div>
<script src="../Scripts/main.js"></script>
<script src="script\main_script.js"></script>
    <script>
        if ("<?php echo $_SESSION["role"];?>" != "organiser") {
            // window.alert("role is STUDENT");
            // Disable the drag feature for creating events.
            scheduler.config.drag_resize = false;
            scheduler.config.drag_move = false;
            scheduler.config.drag_create = false;

            // Disable the double click feature for creating events
            scheduler.config.dblclick_create = false;
        }
    </script>
</body>
</html>
