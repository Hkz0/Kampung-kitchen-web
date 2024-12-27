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

// Delete the recipe
$stmt = $conn->prepare("DELETE FROM recipe WHERE recipe_id = ?");
$stmt->bind_param("i", $recipe_id);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Recipe deleted successfully';
} else {
    $response['message'] = 'Error deleting recipe';
}

echo json_encode($response);
$stmt->close();
$conn->close();
?> 