<?php
require_once __DIR__ . '/cors_headers.php';
session_start();
header('Content-Type: application/json');

include '../config/connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Not authenticated'
    ]);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Update the query to include created_at
    $stmt = $conn->prepare("SELECT recipe_id, name as title, description, image_url, created_at 
                           FROM recipe 
                           WHERE user_id = ?
                           ORDER BY created_at DESC");
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        $recipes[] = [
            'id' => $row['recipe_id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'image_url' => $row['image_url'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'recipes' => $recipes
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error occurred'
    ]);
}

$stmt->close();
$conn->close();
?> 