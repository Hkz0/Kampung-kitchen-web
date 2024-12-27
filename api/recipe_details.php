<?php
include '../config/connect.php'; // Database connection

header('Content-Type: application/json');

$recipe_id = $_GET['recipe_id'];

// Modified query to join with users table to get username
$sql = "SELECT r.*, u.username, e.ethnic_name
        FROM recipe r 
        LEFT JOIN users u ON r.user_id = u.user_id 
        INNER JOIN ethnic e ON r.ethnic_id = e.ethnic_id
        WHERE r.recipe_id = ?";
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
        'instructions' => $row['instructions'],
        'ingredients' => $row['ingredients'],
        'prep_time' => $row['prep_time'],
        'cook_time' => $row['cook_time'],
        'servings' => $row['servings'],
        'user_id' => $row['user_id'],
        'username' => $row['username'],
        'created_at' => $row['created_at'],
        'image_url' => $row['image_url'],
        'ethnic_id' => $row['ethnic_id'],
        'ethnic_name' => $row['ethnic_name']
    ];
}

echo json_encode($recipe);
?>
