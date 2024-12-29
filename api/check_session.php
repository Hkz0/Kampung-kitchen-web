<?php
require_once __DIR__ . '/error_reporting.php';
require_once __DIR__ . '/cors_headers.php';

session_start();
header('Content-Type: application/json');

$response = ['logged_in' => isset($_SESSION['user_id'])];
echo json_encode($response);
?>
