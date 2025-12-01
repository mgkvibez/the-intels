<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'error' => 'Please login first'
    ]);
    exit();
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

// Check required fields
if (empty($input['title']) || empty($input['content']) || empty($input['category']) || empty($input['mediaType'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Please fill all required fields'
    ]);
    exit();
}

try {
    // Prepare SQL with media fields
    $sql = "INSERT INTO articles (title, content, category, mediaType, imageTag, mediaUrl, fileName, timestamp, authorName) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Extract media URL and filename from input
    $mediaUrl = $input['mediaUrl'] ?? '';
    $fileName = $input['fileName'] ?? '';
    
    $result = $stmt->execute([
        $input['title'],
        $input['content'],
        $input['category'],
        $input['mediaType'],
        $input['imageTag'] ?? '',
        $mediaUrl,  // Added
        $fileName,  // Added
        time() * 1000, // Current time in milliseconds
        'OG FX'
    ]);
    
    if ($result) {
        $articleId = $pdo->lastInsertId();
        echo json_encode([
            'success' => true,
            'message' => 'Article published!',
            'article_id' => $articleId
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to save article'
        ]);
    }
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>