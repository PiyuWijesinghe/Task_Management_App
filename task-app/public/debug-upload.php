<?php
// Simple debug script to test file upload functionality
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept');
header('Content-Type: application/json');

// Log all requests for debugging
error_log("Debug upload request: " . $_SERVER['REQUEST_METHOD'] . " from " . $_SERVER['HTTP_ORIGIN'] ?? 'unknown');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple file upload test
    if (isset($_FILES['attachment'])) {
        $uploadDir = '../storage/app/task-attachments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . $_FILES['attachment']['name'];
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadPath)) {
            echo json_encode([
                'success' => true,
                'message' => 'File uploaded successfully (debug mode)',
                'file' => $fileName
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to move uploaded file',
                'error' => error_get_last()
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No file received',
            'post_data' => $_POST,
            'files_data' => $_FILES
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests allowed'
    ]);
}
?>