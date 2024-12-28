<?php
include '../config/connect.php'; // Database connection

header('Content-Type: application/json');
// Query to fetch recipes
$ethnic_id = $_GET['ethnic_id'] ?? null;

$stmt = $conn->prepare("SELECT * FROM recipe WHERE ethnic_id = ? ORDER BY RAND()");
$stmt->bind_param("i", $ethnic_id);
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