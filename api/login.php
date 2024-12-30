<?php
require_once __DIR__ . '/error_reporting.php';
require_once __DIR__ . '/cors_headers.php';
include '../config/connect.php';

session_start();

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];

$stmt = $conn->prepare("SELECT user_id, password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($user_id, $hashed_password);
$stmt->fetch();

$response = [];
if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $response['success'] = true;
    $response['message'] = 'Login successful';
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid username or password';
}

echo json_encode($response);
$stmt->close();
$conn->close();
?>
