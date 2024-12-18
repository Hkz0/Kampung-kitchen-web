
<?php
// Database connection
$db_host = "192.168.0.250";
$db_user = "website";
$db_pass = "website1";
$db_name = "KAMPUNG_KITCHEN";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch recipes
$sql = "SELECT name, description, image_url, prep_time, cook_time, servings FROM recipe";
$result = $conn->query($sql);
?>