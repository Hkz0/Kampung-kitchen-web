<?php
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
$recipe_id = $data['recipe_id'] ?? null;
$name = $data['name'] ?? '';
$description = $data['description'] ?? '';
$instructions = $data['instructions'] ?? '';
$ingredients = $data['ingredients'] ?? '';
$prep_time = $data['prep_time'] ?? 0;
$cook_time = $data['cook_time'] ?? 0;
$servings = $data['servings'] ?? 0;
$image_url = $data['image_url'] ?? '';
$ethnic_id = $data['ethnic_id'] ?? null;
$user_id = $_SESSION['user_id'];

// Ensure the recipe belongs to the logged-in user
$stmt = $conn->prepare("SELECT user_id FROM recipe WHERE recipe_id = ?");
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$stmt->bind_result($owner_id);
$stmt->fetch();
$stmt->close();

if ($owner_id !== $user_id) {
    $response['message'] = 'Unauthorized';
    echo json_encode($response);
    exit;
}

// Update the recipe
$stmt = $conn->prepare("UPDATE recipe SET name = ?, description = ?, instructions = ?, ingredients = ?, prep_time = ?, cook_time = ?, servings = ?, image_url = ?, ethnic_id = ? WHERE recipe_id = ?");
$stmt->bind_param("ssssiiissi", $name, $description, $instructions, $ingredients, $prep_time, $cook_time, $servings, $image_url, $ethnic_id, $recipe_id);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Recipe updated successfully';
} else {
    $response['message'] = 'Error updating recipe';
}

echo json_encode($response);
$stmt->close();
$conn->close();
?> 