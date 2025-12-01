<?php
// logout.php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();
session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully'
]);
?>