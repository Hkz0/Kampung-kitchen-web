<?php
require_once __DIR__ . '/cors_headers.php';
session_start();
$response = ['logged_in' => isset($_SESSION['user_id'])];
echo json_encode($response);
?>
