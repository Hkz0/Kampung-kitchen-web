<?php

// Connect to Database
$host = "192.168.0.250";
$user = "website";
$password = "website1";
$database = "kampung_kitchen";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>