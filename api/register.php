<?php
header('Content-Type: application/json');
include '../config/connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

// Check if username or email already exists
$sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
$result = $conn->query($sql);

$response = array();
if ($result->num_rows > 0) {
    $response['success'] = false;
    $response['message'] = 'Username or email already exists';
} else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error: ' . $conn->error;
    }
}

echo json_encode($response);
$conn->close();
?>

