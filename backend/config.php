
<?php
// Database handling
$db_host = "192.168.0.250";
$db_user = "website";
$db_pass = "website1";
$db_name = "kampung_kitchen";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>