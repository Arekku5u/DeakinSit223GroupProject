<?php

$host = "localhost";
$db = "calendar";
$username = "root";
$password = "Trees&Leaves1";

$dsn = "mysql:host=$host;dbname=$db";

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);
?>