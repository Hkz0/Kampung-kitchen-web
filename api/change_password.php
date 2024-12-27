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
$current_password = $data['current_password'] ?? '';
$new_password = $data['new_password'] ?? '';

// Verify current password
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!password_verify($current_password, $user['password_hash'])) {
    $response['message'] = 'Current password is incorrect';
    echo json_encode($response);
    exit;
}

// Update password
$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
$update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
$update_stmt->bind_param("si", $new_password_hash, $user_id);

if ($update_stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Password updated successfully';
} else {
    $response['message'] = 'Error updating password';
}

echo json_encode($response);
$update_stmt->close();
$conn->close();
?> 