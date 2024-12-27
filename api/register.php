<?php
// At the very top of register.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Include database connection
require_once '../config/connect.php';

// Create users table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
)";

if (!$conn->query($createTable)) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create users table: ' . $conn->error
    ]);
    exit;
}

// Get and validate input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid input data'
    ]);
    exit;
}

$username = $data['username'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);

// Check for existing username
$check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
if (!$check_stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Database prepare failed: ' . $conn->error
    ]);
    exit;
}

$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Username already exists'
    ]);
    exit;
}

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Database prepare failed: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Registration failed: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
