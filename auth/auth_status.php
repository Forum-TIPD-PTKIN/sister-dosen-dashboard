<?php
require_once '../includes/config.php';
require_once '../includes/AuthHelper.php';

// Set content type to JSON
header('Content-Type: application/json');

session_start();

try {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'status':
            // Return authentication status
            echo json_encode([
                'success' => true,
                'data' => AuthHelper::getAuthStatus()
            ]);
            break;
            
        case 'refresh':
            // Refresh token if needed
            if (AuthHelper::refreshTokenIfNeeded()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Token refreshed successfully',
                    'data' => AuthHelper::getAuthStatus()
                ]);
            } else {
                throw new Exception('Token refresh failed');
            }
            break;
            
        case 'check':
            // Simple authentication check
            echo json_encode([
                'success' => true,
                'authenticated' => AuthHelper::isAuthenticated(),
                'remaining_minutes' => AuthHelper::getRemainingSessionTime()
            ]);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'authenticated' => AuthHelper::isAuthenticated()
    ]);
}
?>
