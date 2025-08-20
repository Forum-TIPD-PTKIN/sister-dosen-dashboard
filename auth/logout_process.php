<?php
require_once '../includes/config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

session_start();

try {
    // Clear all session data
    session_unset();
    session_destroy();
    
    // Start a new session
    session_start();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Logout successful',
        'redirect' => 'login.php',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Logout failed: ' . $e->getMessage(),
        'error_code' => 'LOGOUT_FAILED'
    ]);
}
?>
