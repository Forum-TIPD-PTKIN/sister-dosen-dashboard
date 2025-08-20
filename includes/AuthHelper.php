<?php
/**
 * Authentication Helper Class
 * Handles authentication-related operations for SISTER Dashboard
 */

class AuthHelper {
    
    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated() {
        return isset($_SESSION['sister_token']) && 
               isset($_SESSION['token_expiry']) && 
               time() < $_SESSION['token_expiry'];
    }
    
    /**
     * Check if token is about to expire (within 5 minutes)
     */
    public static function isTokenExpiringSoon() {
        if (!isset($_SESSION['token_expiry'])) {
            return true;
        }
        
        return (time() + 300) >= $_SESSION['token_expiry']; // 5 minutes before expiry
    }
    
    /**
     * Get user info from session
     */
    public static function getUserInfo() {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? '',
            'role' => $_SESSION['user_role'] ?? '',
            'login_time' => $_SESSION['login_time'] ?? time(),
            'token_expiry' => $_SESSION['token_expiry'] ?? time()
        ];
    }
    
    /**
     * Get formatted login time
     */
    public static function getFormattedLoginTime() {
        $userInfo = self::getUserInfo();
        if (!$userInfo) {
            return 'Unknown';
        }
        
        return date('d/m/Y H:i', $userInfo['login_time']);
    }
    
    /**
     * Get remaining session time in minutes
     */
    public static function getRemainingSessionTime() {
        if (!isset($_SESSION['token_expiry'])) {
            return 0;
        }
        
        $remaining = $_SESSION['token_expiry'] - time();
        return max(0, floor($remaining / 60));
    }
    
    /**
     * Redirect to login if not authenticated
     */
    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: login.php');
            exit;
        }
    }
    
    /**
     * Clear authentication session
     */
    public static function clearAuth() {
        unset($_SESSION['sister_token']);
        unset($_SESSION['token_expiry']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_id']);
        unset($_SESSION['login_time']);
    }
    
    /**
     * Refresh token if needed
     */
    public static function refreshTokenIfNeeded() {
        if (self::isTokenExpiringSoon()) {
            try {
                require_once 'SisterAPI.php';
                $api = new SisterAPI();
                $newToken = $api->authenticate();
                
                if ($newToken) {
                    $_SESSION['sister_token'] = $newToken;
                    $_SESSION['token_expiry'] = time() + TOKEN_LIFETIME;
                    return true;
                }
            } catch (Exception $e) {
                error_log('Token refresh failed: ' . $e->getMessage());
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get JSON response for authentication status
     */
    public static function getAuthStatus() {
        return [
            'authenticated' => self::isAuthenticated(),
            'expires_soon' => self::isTokenExpiringSoon(),
            'remaining_minutes' => self::getRemainingSessionTime(),
            'user' => self::getUserInfo()
        ];
    }
}
?>
