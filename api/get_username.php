<?php
require_once __DIR__ . '/cors_headers.php';
session_start();
$response = [];

if (isset($_SESSION['username'])) {
    $response['success'] = true;
    $response['username'] = $_SESSION['username'];
} else {
    $response['success'] = false;
}

echo json_encode($response);
?>
