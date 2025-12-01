<?php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests (PRESERVED)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    
    // Category filtering (PRESERVED - just added author filter)
    if ($category === 'all') {
        $sql = "SELECT * FROM articles WHERE authorName = 'OG FX' ORDER BY timestamp DESC";
        $stmt = $pdo->query($sql);
    } else {
        $sql = "SELECT * FROM articles WHERE category = ? AND authorName = 'OG FX' ORDER BY timestamp DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$category]);
    }
    
    $articles = $stmt->fetchAll();
    
    // Timestamp conversion (PRESERVED)
    foreach ($articles as &$article) {
        $article['timestamp'] = (int)$article['timestamp'];
    }
    
    // Same response format (PRESERVED)
    echo json_encode([
        'success' => true,
        'articles' => $articles,
        'count' => count($articles)
    ]);
    
} catch(Exception $e) {
    // Same error handling (PRESERVED)
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ]);
}
?>