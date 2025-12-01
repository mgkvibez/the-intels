<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$input = json_decode(file_get_contents('php://input'), true);

$username = isset($input['username']) ? trim($input['username']) : '';
$password = isset($input['password']) ? $input['password'] : '';

// Hardcoded login (you can change these)
$correct_username = 'OG FX';
$correct_password = 'OG123$$$';

if ($username === $correct_username && $password === $correct_password) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    
    echo json_encode([
        'success' => true,
        'message' => 'Login successful'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Wrong username or password'
    ]);
}
?>