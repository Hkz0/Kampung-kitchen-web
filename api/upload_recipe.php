<?php
require_once __DIR__ . '/cors_headers.php';
session_start();
include '../config/connect.php';

header('Content-Type: application/json');

$response = ['success' => false];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Not authenticated';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];
$name = $data['name'] ?? '';
$description = $data['description'] ?? '';
$instructions = $data['instructions'] ?? '';
$ingredients = $data['ingredients'] ?? '';
$prep_time = $data['prep_time'] ?? 0;
$cook_time = $data['cook_time'] ?? 0;
$servings = $data['servings'] ?? 0;
$image_url = $data['image_url'] ?? '';
$ethnic_id = $data['ethnic_id'] ?? null;

$stmt = $conn->prepare("INSERT INTO recipe (name, description, instructions, ingredients, prep_time, cook_time, servings, user_id, image_url, ethnic_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiiisss", $name, $description, $instructions, $ingredients, $prep_time, $cook_time, $servings, $user_id, $image_url, $ethnic_id);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Recipe uploaded successfully';
} else {
    $response['message'] = 'Error uploading recipe';
}

echo json_encode($response);
$stmt->close();
$conn->close();
?> 