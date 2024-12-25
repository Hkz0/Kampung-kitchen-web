<?php
include '../config/connect.php'; // Database connection

header('Content-Type: application/json');

$recipe_id = $_GET['recipe_id'];

// Query to fetch recipe details
$sql = "SELECT * FROM recipe WHERE recipe_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

$recipe = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $recipe = [
        'recipe_id' => $row['recipe_id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'image_url' => $row['image_url'],
        'prep_time' => $row['prep_time'],
        'cook_time' => $row['cook_time'],
        'servings' => $row['servings'],
        'ingredients' => explode("\n", $row['ingredients']),
        'instructions' => explode("\n", $row['instructions'])
    ];
}

echo json_encode($recipe);
?>
