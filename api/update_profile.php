<?php
session_start();
include '../config/connect.php';

$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';

// Check if username already exists (excluding current user)
$check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
$check_stmt->bind_param("si", $username, $user_id);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    $response['message'] = 'Username already exists';
    echo json_encode($response);
    exit;
}
$check_stmt->close();

// Update user information
$stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
$stmt->bind_param("ssi", $username, $email, $user_id);

if ($stmt->execute()) {
    $_SESSION['username'] = $username; // Update session username
    $response['success'] = true;
    $response['message'] = 'Profile updated successfully';
} else {
    $response['message'] = 'Error updating profile';
}

echo json_encode($response);
$stmt->close();
$conn->close();
?> 