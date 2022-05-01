<?php
$host = 'localhost';
$root = 'umeskias_umsportal_user';
$pass = 'iLimAb1Yt0h2';
$database = 'umeskias_umsportal';
$db = mysqli_connect($host, $root, $pass, $database);
if (!$db) {
    $msg =  "WEBSITE NOT CONNECTED TO THE DATABASE";
} else {
    $msg = "CONNECTED TO THE DATABASE";
}
$now = date_create();
$eaa = date_timestamp_get($now);
$time = $eaa + 10800;
