<?php
require_once '../includes/config.php';
require_once '../includes/SisterAPI.php';

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
    // Check if user is already logged in
    if (isset($_SESSION['sister_token']) && isset($_SESSION['token_expiry']) && time() < $_SESSION['token_expiry']) {
        echo json_encode([
            'success' => true, 
            'message' => 'Already logged in',
            'redirect' => 'index.php'
        ]);
        exit;
    }

    // Initialize API and authenticate
    $api = new SisterAPI();
    $token = $api->authenticate();
    
    if ($token) {
        // Store token and user info in session
        $_SESSION['sister_token'] = $token;
        $_SESSION['token_expiry'] = time() + TOKEN_LIFETIME;
        $_SESSION['user_role'] = SISTER_ROLE;
        $_SESSION['user_id'] = SISTER_USER_ID;
        $_SESSION['login_time'] = time();
        
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Login successful! Redirecting to dashboard...',
            'redirect' => 'index.php',
            'user' => [
                'role' => SISTER_ROLE,
                'id' => SISTER_USER_ID,
                'login_time' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        throw new Exception('Authentication failed - Invalid response from server');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Login failed: ' . $e->getMessage(),
        'error_code' => 'AUTH_FAILED'
    ]);
}
?>
