<?php
session_start();
header('Content-Type: application/json');

$response = array();
if (isset($_SESSION['username'])) {
    include '../config/connect.php';
    $username = $_SESSION['username'];
    $query = "SELECT username, email FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $response['success'] = true;
    $response['user'] = $user;
    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
}

echo json_encode($response);
?>
