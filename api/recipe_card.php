<?php
include '../config/connect.php'; // Database connection

header('Content-Type: application/json');
// Query to fetch recipes
$sql = "SELECT * FROM recipe ORDER BY RAND()";
$result = $conn->query($sql);

$recipes = [];
// Check if there are any recipes
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recipes[] = [
            'recipe_id' => $row['recipe_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image_url' => $row['image_url'],
            'prep_time' => $row['prep_time'],
            'cook_time' => $row['cook_time'],
            'servings' => $row['servings']
        ];
    }
} 

echo json_encode($recipes);

?>