<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$articleId = isset($input['id']) ? intval($input['id']) : 0;

if ($articleId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid article ID']);
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $result = $stmt->execute([$articleId]);
    
    if ($result && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Article deleted']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Article not found']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>