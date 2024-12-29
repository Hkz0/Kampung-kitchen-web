<?php
require_once __DIR__ . '/cors_headers.php';
session_start();
include '../config/connect.php';

$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT user_id, username, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $response['success'] = true;
    $response['user'] = [
        'id' => $user['user_id'],
        'username' => $user['username'],
        'email' => $user['email'] ?? ''
    ];
} else {
    $response['message'] = 'User not found';
}

echo json_encode($response);
$stmt->close();
$conn->close();
?> 