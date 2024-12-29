<?php
require_once __DIR__ . '/cors_headers.php';
session_start();
session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully'
]);
?> 