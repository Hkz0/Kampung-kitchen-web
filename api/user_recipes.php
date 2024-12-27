<?php
session_start();
include '../config/connect.php';

$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Not logged in';
    echo json_encode($response);
    exit;
}

$user_id = $_GET['user_id'] ?? $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, title, description, image_url FROM recipes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$recipes = [];
while ($row = $result->fetch_assoc()) {
    $recipes[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'image_url' => $row['image_url']
    ];
}

$response['success'] = true;
$response['recipes'] = $recipes;

echo json_encode($response);
$stmt->close();
$conn->close();
?> 