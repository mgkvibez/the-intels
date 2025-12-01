<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$articleId = isset($input['id']) ? intval($input['id']) : 0;
$title = isset($input['title']) ? trim($input['title']) : '';
$content = isset($input['content']) ? trim($input['content']) : '';
$category = isset($input['category']) ? $input['category'] : '';
$mediaType = isset($input['mediaType']) ? $input['mediaType'] : '';
$imageTag = isset($input['imageTag']) ? trim($input['imageTag']) : '';
$mediaUrl = isset($input['mediaUrl']) ? $input['mediaUrl'] : null;
$fileName = isset($input['fileName']) ? $input['fileName'] : null;

if ($articleId <= 0 || empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit();
}

try {
    // If mediaUrl is provided, update it, otherwise keep existing
    if ($mediaUrl !== null) {
        $sql = "UPDATE articles SET title = ?, content = ?, category = ?, mediaType = ?, imageTag = ?, mediaUrl = ?, fileName = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$title, $content, $category, $mediaType, $imageTag, $mediaUrl, $fileName, $articleId]);
    } else {
        $sql = "UPDATE articles SET title = ?, content = ?, category = ?, mediaType = ?, imageTag = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$title, $content, $category, $mediaType, $imageTag, $articleId]);
    }
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Article updated']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Update failed']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>