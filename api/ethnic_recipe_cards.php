<?php
require_once __DIR__ . '/cors_headers.php';
include '../config/connect.php';

header('Content-Type: application/json');

$ethnic_id = $_GET['ethnic_id'] ?? null;

if (!$ethnic_id) {
    echo json_encode(['error' => 'No ethnic_id provided']);
    exit;
}

// Add error logging
error_log("Fetching recipes for ethnic_id: " . $ethnic_id);

// Modified query to join with ethnic table to get ethnic name and description
$stmt = $conn->prepare("SELECT r.*, e.ethnic_name, e.desc as ethnic_description 
                       FROM recipe r 
                       INNER JOIN ethnic e ON r.ethnic_id = e.ethnic_id 
                       WHERE r.ethnic_id = ?");

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$stmt->bind_param("i", $ethnic_id);
$success = $stmt->execute();

if (!$success) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['error' => 'Query execution failed']);
    exit;
}

$result = $stmt->get_result();
$recipes = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recipes[] = [
            'recipe_id' => $row['recipe_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'instructions' => $row['instructions'],
            'ingredients' => $row['ingredients'],
            'prep_time' => $row['prep_time'],
            'cook_time' => $row['cook_time'],
            'servings' => $row['servings'],
            'user_id' => $row['user_id'],
            'created_at' => $row['created_at'],
            'image_url' => $row['image_url'],
            'ethnic_id' => $row['ethnic_id'],
            'ethnic_name' => $row['ethnic_name'],
            'ethnic_description' => $row['ethnic_description']
        ];
    }
}

error_log("Found " . count($recipes) . " recipes for ethnic_id: " . $ethnic_id);
echo json_encode($recipes);

$stmt->close();
$conn->close();
?>