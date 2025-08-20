<?php
require_once 'includes/config.php';

session_start();

// Check if logout was processed
$logoutProcessed = isset($_GET['processed']) && $_GET['processed'] === 'true';

// If not processed yet, redirect to logout process
if (!$logoutProcessed) {
    header('Location: auth/logout_process.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTER Dashboard - Logout</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .logout-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 3rem;
        }
        
        .logout-icon {
            color: #1cc88a;
            margin-bottom: 1rem;
        }
        
        .btn-return {
            background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.4);
        }
    </style>
    
    <!-- Auto redirect after 5 seconds -->
    <meta http-equiv="refresh" content="5;url=login.php">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="logout-card">
                    <i class="fas fa-check-circle fa-4x logout-icon"></i>
                    <h3 class="mb-3">Logout Berhasil</h3>
                    <p class="text-muted mb-4">
                        Anda telah berhasil keluar dari SISTER Dashboard.
                        <br>
                        Session dan token telah dihapus dari sistem.
                    </p>
                    
                    <div class="mb-4">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%" id="redirectProgress"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            Otomatis mengarahkan ke halaman login dalam <span id="countdown">5</span> detik...
                        </small>
                    </div>
                    
                    <a href="login.php" class="btn btn-primary btn-return">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Kembali ke Login
                    </a>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Logout time: <?php echo date('d/m/Y H:i:s'); ?>
                            <br>
                            <i class="fas fa-info-circle me-1"></i>
                            Terima kasih telah menggunakan SISTER Dashboard
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Countdown and progress bar
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const progressBar = document.getElementById('redirectProgress');
        
        const timer = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown;
            
            // Update progress bar
            const progress = ((5 - countdown) / 5) * 100;
            progressBar.style.width = progress + '%';
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'login.php';
            }
        }, 1000);
    </script>
</body>
</html>
