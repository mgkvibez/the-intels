<?php
// upload_media.php
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Check if user is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Please login first']);
    exit();
}

// Check if file was uploaded
if (!isset($_FILES['media']) || $_FILES['media']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
    exit();
}

$file = $_FILES['media'];
$originalName = $file['name'];

// File validation
$maxSize = 10 * 1024 * 1024; // 10MB
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'error' => 'File too large. Max 10MB']);
    exit();
}

// Allowed file types
$allowedTypes = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'video/mp4', 'video/webm', 'video/ogg',
    'audio/mpeg', 'audio/wav', 'audio/ogg',
    'application/pdf'
];

if (!in_array($file['type'], $allowedTypes)) {
    // Check by extension as fallback
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'ogg', 'mp3', 'wav', 'pdf'];
    
    if (!in_array($extension, $allowedExtensions)) {
        echo json_encode(['success' => false, 'error' => 'File type not allowed']);
        exit();
    }
}

// Create uploads directory if it doesn't exist
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'error' => 'Failed to create upload directory']);
        exit();
    }
}

// Check if directory is writable
if (!is_writable($uploadDir)) {
    if (!chmod($uploadDir, 0777)) {
        echo json_encode(['success' => false, 'error' => 'Upload directory is not writable']);
        exit();
    }
}

// Generate unique filename
$safeName = preg_replace('/[^a-zA-Z0-9\-\._]/', '_', $originalName);
$uniqueName = time() . '_' . uniqid() . '_' . $safeName;
$filePath = $uploadDir . $uniqueName;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filePath)) {
    // Get the base URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // For InfinityFree, you might need to use a different approach
    // Let's create a relative URL that works
    $relativePath = 'uploads/' . $uniqueName;
    
    echo json_encode([
        'success' => true, 
        'fileUrl' => $relativePath, 
        'fileName' => $originalName,
        'fileSize' => $file['size'],
        'fileType' => $file['type']
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save file. Check directory permissions.']);
}
?>