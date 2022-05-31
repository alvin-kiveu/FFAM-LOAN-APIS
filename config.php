<?php

$host = 'localhost';

$root = 'empirein_amnia_user';

$pass = '@usUgB)UBn*(';

$database = 'empirein_aminia';

$db = mysqli_connect($host, $root, $pass, $database);

if (!$db) {

    $msg =  "WEBSITE NOT CONNECTED TO THE DATABASE";
} else {

    $msg = "CONNECTED TO THE DATABASE";
}

$now = date_create();

$eaa = date_timestamp_get($now);

$time = $eaa + 10800;