<?php
include 'cors_headers.php';
include '../config/connect.php'; // Database connection

header('Content-Type: application/json');

$searchTerm = $_GET['search'] ?? '';

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM recipe WHERE name LIKE ? OR description LIKE ? ORDER BY RAND()");
$searchTerm = "%$searchTerm%"; // Add wildcards for LIKE
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$recipes = [];
// Check if there are any recipes
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
            'ethnic_id' => $row['ethnic_id']
        ];
    }
} 

echo json_encode($recipes);

$stmt->close();
$conn->close();
?> 