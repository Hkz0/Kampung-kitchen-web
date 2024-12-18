<?php
// database connection
$db_host = "192.168.0.250";
$db_user = "website";
$db_pass = "website1";
$db_name = "KAMPUNG_KITCHEN";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {// check connection
    die("Connection failed: " . mysqli_connect_error());
}
