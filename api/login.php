<?php
session_start();
include '../config/connect.php'; // Database connection

// Get the data from the request
$data = json_decode(file_get_contents('php://input'), true);
$usernameOrEmail = $data['username'];
$password = $data['password'];

// Query to fetch the user
$sql = "SELECT * FROM users WHERE username='$usernameOrEmail' OR email='$usernameOrEmail'";
$result = $conn->query($sql);

// Check if the user exists
$response = array();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $user['hash_password'])) {
        $_SESSION['username'] = $user['username'];
        $response['success'] = true;
    } else {
        $response['success'] = false;
    }
} else {
    $response['success'] = false;
}

// Send the response
echo json_encode($response);
$conn->close();
?>
