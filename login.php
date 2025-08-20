<?php
require_once 'includes/config.php';

session_start();

// Check if user is already logged in
if (isset($_SESSION['sister_token']) && isset($_SESSION['token_expiry']) && time() < $_SESSION['token_expiry']) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTER Dashboard - Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .login-header {
            background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 2rem;
            text-align: center;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e3e6f0;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .loading-spinner {
            display: none;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                        <h3 class="mb-0">SISTER Dashboard</h3>
                        <p class="mb-0">Sistem Informasi SDM Diktiristek</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Alert Container -->
                        <div id="alertContainer"></div>
                        
                        <form id="loginForm">
                            <div class="text-center mb-4">
                                <h5 class="text-gray-900">Masuk ke Dashboard</h5>
                                <p class="text-muted">Menggunakan kredensial Sister-WS Basic</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>
                                    Role
                                </label>
                                <input type="text" class="form-control" value="<?php echo SISTER_ROLE; ?>" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-id-card me-2"></i>
                                    User ID
                                </label>
                                <input type="text" class="form-control" value="<?php echo substr(SISTER_USER_ID, 0, 8); ?>..." readonly>
                            </div>
                            
                            <div class="mb-4">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Kredensial telah dikonfigurasi secara otomatis. Klik login untuk masuk ke dashboard.
                                </small>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login" id="loginBtn">
                                    <span class="normal-text">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Masuk ke Dashboard
                                    </span>
                                    <span class="loading-spinner">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Sedang masuk...
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Sistem keamanan menggunakan JWT Token<br>
                                <i class="fas fa-clock me-1"></i>
                                Session timeout: <?php echo TOKEN_LIFETIME / 60; ?> menit
                            </small>
                        </div>
                        
                        <!-- Connection Status -->
                        <div class="text-center mt-3">
                            <small class="text-muted" id="connectionStatus">
                                <i class="fas fa-wifi me-1 text-success"></i>
                                Connected to SISTER API
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <small class="text-white-50">
                        © <?php echo date('Y'); ?> SISTER Dashboard PHP
                        <br>
                        <i class="fas fa-server me-1"></i>
                        API: <?php echo SISTER_API_BASE_URL; ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Login Form Handler -->
    <script>
        class LoginHandler {
            constructor() {
                this.form = document.getElementById('loginForm');
                this.btn = document.getElementById('loginBtn');
                this.normalText = this.btn.querySelector('.normal-text');
                this.loadingSpinner = this.btn.querySelector('.loading-spinner');
                this.alertContainer = document.getElementById('alertContainer');
                
                this.init();
            }
            
            init() {
                this.form.addEventListener('submit', (e) => this.handleSubmit(e));
                this.checkAPIConnection();
            }
            
            async handleSubmit(e) {
                e.preventDefault();
                
                this.showLoading(true);
                this.clearAlerts();
                
                try {
                    const response = await fetch('auth/login_process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({})
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showAlert('success', data.message);
                        
                        // Add pulse effect to success
                        this.btn.classList.add('pulse');
                        
                        // Redirect after 2 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect || 'index.php';
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Login failed');
                    }
                    
                } catch (error) {
                    console.error('Login error:', error);
                    this.showAlert('danger', error.message || 'Connection error. Please try again.');
                    this.showLoading(false);
                }
            }
            
            showLoading(loading) {
                if (loading) {
                    this.normalText.style.display = 'none';
                    this.loadingSpinner.style.display = 'inline';
                    this.btn.disabled = true;
                } else {
                    this.normalText.style.display = 'inline';
                    this.loadingSpinner.style.display = 'none';
                    this.btn.disabled = false;
                    this.btn.classList.remove('pulse');
                }
            }
            
            showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
                
                const alert = document.createElement('div');
                alert.className = `alert ${alertClass} alert-dismissible fade show`;
                alert.innerHTML = `
                    <i class="${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                this.alertContainer.appendChild(alert);
                
                // Auto-dismiss after 5 seconds for error alerts
                if (type !== 'success') {
                    setTimeout(() => {
                        if (alert.parentNode) {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 5000);
                }
            }
            
            clearAlerts() {
                this.alertContainer.innerHTML = '';
            }
            
            async checkAPIConnection() {
                try {
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 5000);
                    
                    const response = await fetch('<?php echo SISTER_API_BASE_URL; ?>/referensi/agama', {
                        method: 'GET',
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    
                    const statusElement = document.getElementById('connectionStatus');
                    if (response.status === 401 || response.ok) {
                        // 401 is expected without token, means API is reachable
                        statusElement.innerHTML = '<i class="fas fa-wifi me-1 text-success"></i>Connected to SISTER API';
                    } else {
                        statusElement.innerHTML = '<i class="fas fa-exclamation-triangle me-1 text-warning"></i>API connection issue';
                    }
                } catch (error) {
                    document.getElementById('connectionStatus').innerHTML = 
                        '<i class="fas fa-wifi me-1 text-danger"></i>Cannot reach SISTER API';
                }
            }
        }
        
        // Initialize login handler when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            new LoginHandler();
        });
    </script>
</body>
</html>
