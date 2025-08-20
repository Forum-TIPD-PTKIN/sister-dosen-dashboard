// Authentication Module
class AuthManager {
    constructor() {
        this.token = localStorage.getItem('sister_token');
        this.tokenExpiry = localStorage.getItem('sister_token_expiry');
        this.userInfo = JSON.parse(localStorage.getItem('sister_user_info') || '{}');
    }

    // Check if token is valid and not expired
    isTokenValid() {
        if (!this.token || !this.tokenExpiry) {
            return false;
        }
        
        const now = new Date().getTime();
        const expiry = parseInt(this.tokenExpiry);
        
        return now < expiry;
    }

    // Authenticate and get token
    async authenticate() {
        try {
            showLoading(true);
            
            const response = await fetch(`${API_CONFIG.baseURL}/authorize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: API_CONFIG.credentials.username,
                    password: API_CONFIG.credentials.password
                })
            });

            if (!response.ok) {
                throw new Error(`Authentication failed: ${response.status}`);
            }

            const data = await response.json();
            
            // Store token with expiry (60 minutes as per API docs)
            this.token = data.token;
            const expiryTime = new Date().getTime() + (60 * 60 * 1000); // 60 minutes
            this.tokenExpiry = expiryTime.toString();
            
            localStorage.setItem('sister_token', this.token);
            localStorage.setItem('sister_token_expiry', this.tokenExpiry);
            localStorage.setItem('sister_user_info', JSON.stringify({
                id: API_CONFIG.credentials.id_pengguna,
                role: API_CONFIG.credentials.role,
                loginTime: new Date().toISOString()
            }));

            this.userInfo = {
                id: API_CONFIG.credentials.id_pengguna,
                role: API_CONFIG.credentials.role,
                loginTime: new Date().toISOString()
            };

            return true;
        } catch (error) {
            console.error('Authentication error:', error);
            showError('Failed to authenticate: ' + error.message);
            return false;
        } finally {
            showLoading(false);
        }
    }

    // Get authorization header
    getAuthHeader() {
        return {
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': 'application/json'
        };
    }

    // Logout
    logout() {
        this.token = null;
        this.tokenExpiry = null;
        this.userInfo = {};
        
        localStorage.removeItem('sister_token');
        localStorage.removeItem('sister_token_expiry');
        localStorage.removeItem('sister_user_info');
        
        // Redirect to login or reload page
        window.location.reload();
    }

    // Auto-refresh token
    async refreshTokenIfNeeded() {
        if (!this.isTokenValid()) {
            console.log('Token expired, re-authenticating...');
            return await this.authenticate();
        }
        return true;
    }

    // Get user info for display
    getUserDisplayInfo() {
        return {
            role: this.userInfo.role || 'Unknown',
            loginTime: this.userInfo.loginTime ? new Date(this.userInfo.loginTime).toLocaleString() : 'Unknown'
        };
    }
}

// Global auth manager instance
const authManager = new AuthManager();

// Initialize authentication on page load
$(document).ready(async function() {
    // Update user info display
    const userInfo = authManager.getUserDisplayInfo();
    $('#userInfo').text(`${userInfo.role} | Login: ${userInfo.loginTime}`);
    
    // Check if we need to authenticate
    if (!authManager.isTokenValid()) {
        const success = await authManager.authenticate();
        if (!success) {
            showError('Failed to authenticate. Please check your credentials.');
            return;
        }
    }
    
    // Initialize dashboard
    initializeDashboard();
});

// Logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        authManager.logout();
    }
}
