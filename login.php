<?php
session_start();
require_once 'includes/config.php';
require_once 'backend/inc/config.php';

// Check active session status (tidak auto-redirect agar pengguna bisa ganti akun / demo)
$isLoggedIn = (isset($_SESSION['login']) && $_SESSION['login'] == 1) ||
              (isset($_SESSION['sister_token']) && isset($_SESSION['token_expiry']) && time() < $_SESSION['token_expiry']);
$activeRole = $_SESSION['user_role'] ?? $_SESSION['full_name'] ?? $_SESSION['username'] ?? ($isLoggedIn ? 'Pengguna SISTER' : null);
$activeDashboard = (isset($_SESSION['login']) && $_SESSION['login'] == 1) ? 'backend/index.php' : 'index.php';

$error = '';
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
          (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);

// Process Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $jsonData = json_decode($raw, true) ?: [];
        $username = trim($jsonData['username'] ?? $jsonData['email'] ?? '');
        $password = trim($jsonData['password'] ?? '');
        $remember = !empty($jsonData['remember']);
    } else {
        $username = trim($_POST['username'] ?? $_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $remember = !empty($_POST['remember']);
    }

    $loginSuccess = false;
    $redirectUrl = 'index.php';
    $message = '';

    // 1. Super Admin Demo Account
    if (($username === 'admin@ftipd.org' && ($password === 'P4ssword@admin' || $password === 'admin123')) ||
        ($username === 'admin' && ($password === 'admin123' || $password === 'P4ssword@admin'))) {
        $_SESSION['login'] = 1;
        $_SESSION['id_user'] = 10;
        $_SESSION['username'] = 'admin';
        $_SESSION['full_name'] = 'Super Administrator';
        $_SESSION['group_level'] = 'administrator';
        $_SESSION['sister_token'] = 'sister_admin_' . bin2hex(random_bytes(16));
        $_SESSION['token_expiry'] = time() + ($remember ? 86400 * 30 : 7200);
        $_SESSION['user_role'] = 'Administrator SISTER';
        $_SESSION['user_id'] = 'admin';
        $_SESSION['login_time'] = time();

        $loginSuccess = true;
        $redirectUrl = 'backend/index.php';
        $message = 'Login Super Admin berhasil! Mengalihkan ke Panel Admin...';
    }
    // 2. Demo SISTER / Operator Account
    elseif (($username === 'sister@ftipd.org' && ($password === 'P4ssword@sister' || $password === 'demo123')) ||
            ($username === 'demo' && ($password === 'demo123' || $password === 'demo' || $password === 'P4ssword@sister')) ||
            ($username === 'demo@ftipd.org')) {
        $_SESSION['sister_token'] = 'demo_token_' . bin2hex(random_bytes(16));
        $_SESSION['token_expiry'] = time() + ($remember ? 86400 * 30 : 7200);
        $_SESSION['user_role'] = 'Sister-WS Consumer Basic (Demo)';
        $_SESSION['user_id'] = 'demo-user-sister';
        $_SESSION['login_time'] = time();

        $loginSuccess = true;
        $redirectUrl = 'index.php';
        $message = 'Login Demo SISTER berhasil! Mengalihkan ke dashboard...';
    }
    // 3. Database Check sys_users
    else {
        try {
            $data = [
                'username' => $username,
                'password' => md5($password)
            ];
            $check = $db->checkExist('sys_users', $data);
            if ($check == true) {
                $_SESSION['group_level'] = $check->getData()->group_level;
                $_SESSION['id_user'] = $check->getData()->id;
                $_SESSION['username'] = $check->getData()->username;
                $_SESSION['full_name'] = $check->getData()->full_name;
                $_SESSION['login'] = 1;
                $_SESSION['sister_token'] = 'sister_token_' . bin2hex(random_bytes(16));
                $_SESSION['token_expiry'] = time() + ($remember ? 86400 * 30 : 7200);
                $_SESSION['user_role'] = $check->getData()->group_level;
                $_SESSION['user_id'] = $check->getData()->username;
                $_SESSION['login_time'] = time();

                $loginSuccess = true;
                $redirectUrl = 'backend/index.php';
                $message = 'Login berhasil! Mengalihkan...';
            } else {
                $emailData = [
                    'email' => $username,
                    'password' => md5($password)
                ];
                $checkEmail = $db->checkExist('sys_users', $emailData);
                if ($checkEmail == true) {
                    $_SESSION['group_level'] = $checkEmail->getData()->group_level;
                    $_SESSION['id_user'] = $checkEmail->getData()->id;
                    $_SESSION['username'] = $checkEmail->getData()->username;
                    $_SESSION['full_name'] = $checkEmail->getData()->full_name;
                    $_SESSION['login'] = 1;
                    $_SESSION['sister_token'] = 'sister_token_' . bin2hex(random_bytes(16));
                    $_SESSION['token_expiry'] = time() + ($remember ? 86400 * 30 : 7200);
                    $_SESSION['user_role'] = $checkEmail->getData()->group_level;
                    $_SESSION['user_id'] = $checkEmail->getData()->username;
                    $_SESSION['login_time'] = time();

                    $loginSuccess = true;
                    $redirectUrl = 'backend/index.php';
                    $message = 'Login berhasil! Mengalihkan...';
                }
            }
        } catch (Exception $e) {
            // ignore
        }

        if (!$loginSuccess) {
            $error = 'Email/Username atau Password tidak cocok. Silakan gunakan kartu Akses Cepat Akun Demo di atas.';
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        if ($loginSuccess) {
            echo json_encode([
                'success' => true,
                'message' => $message,
                'redirect' => $redirectUrl
            ]);
        } else {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => $error
            ]);
        }
        exit;
    } else {
        if ($loginSuccess) {
            header("Location: $redirectUrl");
            exit;
        }
    }
}

// Global settings
$logoUrl = getPengaturan('logo') ?: 'https://is3.cloudhost.id/sidimpuan/17555274961795202471.png';
$favicon = getPengaturan('favicon') ?: 'https://is3.cloudhost.id/sidimpuan/1754571431148243033.png';
$appName = getPengaturan('app_name') ?: 'SISTER Dashboard';
$campusName = getPengaturan('nama_kampus') ?: 'UIN Syekh Ali Hasan Ahmad Addary Padangsidimpuan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($appName) ?> - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($favicon) ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-islami { background-color: #0F9D58; }
        .text-islami { color: #0F9D58; }
        .border-islami { border-color: #0F9D58; }
        .focus-ring-islami:focus { --tw-ring-color: rgba(15, 157, 88, 0.5); border-color: #0F9D58; }
        @media (max-width: 767px) {
            .mobile-hide { display: none !important; }
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-white selection:bg-[#0F9D58] selection:text-white">
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen w-full bg-white">
        
        <!-- Left Side: Clean Modern Form -->
        <div class="w-full h-full flex flex-col justify-center items-center px-6 sm:px-12 py-12 relative bg-white overflow-y-auto" style="border-right: 1px solid #e2e8f0;">
            
            <div class="w-full max-w-md relative z-10">
                
                <!-- Identitas Institusi / App Title -->
                <div class="text-center mb-8">
                    <?php if (!empty($logoUrl)): ?>
                        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo Instansi" class="h-16 w-auto object-contain mx-auto mb-5">
                    <?php else: ?>
                        <div class="h-16 w-16 bg-gradient-to-br from-[#0F9D58] to-emerald-400 rounded-2xl flex items-center justify-center shadow-lg shadow-green-200/50 mx-auto mb-5">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                    <?php endif; ?>
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight leading-tight">
                        Selamat Datang
                    </h1>
                    <p class="text-sm font-semibold text-slate-500 mt-2 uppercase tracking-wide">
                        Masuk ke SISTER Dashboard
                    </p>
                </div>

                <!-- Alert Container -->
                <div id="alertContainer">
                    <?php if (!empty($error)): ?>
                        <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                <span><?= htmlspecialchars($error) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Status Sesi Aktif (Jika sudah login) -->
                <?php if ($isLoggedIn): ?>
                    <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-950 text-xs shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white shadow-sm flex-shrink-0">
                                <i class="fas fa-user-check text-xs"></i>
                            </span>
                            <div>
                                <span class="font-bold text-emerald-900">Sesi Aktif</span>
                                <div class="text-[11px] text-emerald-800 font-medium">
                                    Sedang masuk sebagai: <strong><?= htmlspecialchars($activeRole) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="<?= $activeDashboard ?>" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-[11px] shadow-sm transition-all">
                                Ke Dashboard &rarr;
                            </a>
                            <a href="logout.php" class="px-2.5 py-1.5 bg-white hover:bg-red-50 text-red-600 border border-red-200 rounded-xl font-bold text-[11px] transition-all">
                                Logout
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Kartu Akun Demo (Demo Accounts Quick Access) -->
                <div class="mb-6 p-3 rounded-2xl bg-gradient-to-br from-emerald-50 via-teal-50/40 to-slate-50 border border-emerald-200/80 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-600 text-white text-[10px] font-black shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </span>
                            <span class="text-xs font-black uppercase tracking-wider text-emerald-950">Akses Cepat Akun Demo</span>
                        </div>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-100/70 px-2 py-0.5 rounded-full">Klik untuk Isi</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <!-- Super Admin Card -->
                        <div onclick="fillDemo('admin@ftipd.org', 'P4ssword@admin')" class="p-2.5 rounded-xl bg-white border border-emerald-100 hover:border-emerald-500 hover:shadow-md cursor-pointer transition-all duration-200 active:scale-[0.98] group">
                            <div class="flex items-center justify-between mb-0.5">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="font-bold text-slate-800 text-[11px] group-hover:text-emerald-700 transition-colors">Super Admin</span>
                                </div>
                                <span class="text-[9px] font-bold text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">Pakai &rarr;</span>
                            </div>
                            <div class="text-[10px] text-slate-600 font-mono truncate">
                                admin@ftipd.org
                            </div>
                        </div>

                        <!-- Operator Admin / Demo Sister Card -->
                        <div onclick="fillDemo('sister@ftipd.org', 'P4ssword@sister')" class="p-2.5 rounded-xl bg-white border border-emerald-100 hover:border-emerald-500 hover:shadow-md cursor-pointer transition-all duration-200 active:scale-[0.98] group">
                            <div class="flex items-center justify-between mb-0.5">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="font-bold text-slate-800 text-[11px] group-hover:text-blue-700 transition-colors">Demo SISTER</span>
                                </div>
                                <span class="text-[9px] font-bold text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">Pakai &rarr;</span>
                            </div>
                            <div class="text-[10px] text-slate-600 font-mono truncate">
                                sister@ftipd.org
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function fillDemo(email, password) {
                        const emailInput = document.getElementById('email');
                        const passInput = document.getElementById('password');
                        if (emailInput && passInput) {
                            emailInput.value = email;
                            passInput.value = password;
                            emailInput.focus();
                            // Visual feedback
                            emailInput.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                            passInput.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                            setTimeout(() => {
                                emailInput.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                                passInput.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50/50');
                            }, 1000);
                        }
                    }
                </script>

                <form id="loginForm" method="POST" action="login.php" class="space-y-6">
                    <div class="space-y-5">
                        <!-- Email Address / Username -->
                        <div>
                            <div class="relative rounded-xl shadow-sm group">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none transition-colors group-focus-within:text-[#0F9D58]" style="padding-left: 1rem;">
                                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-[#0F9D58] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                </div>
                                <input id="email" name="username" type="text" autocomplete="username" required class="block w-full bg-slate-50 border border-slate-200 rounded-xl placeholder-slate-400 text-slate-900 focus:bg-white focus:outline-none focus:border-[#0F9D58] focus:ring-1 focus:ring-[#0F9D58] transition-all duration-300 sm:text-sm font-medium shadow-sm" placeholder="Email Address / Username" value="" style="padding-top: 0.875rem; padding-bottom: 0.875rem; padding-left: 3rem; padding-right: 1rem;">
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="relative rounded-xl shadow-sm group">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none transition-colors group-focus-within:text-[#0F9D58]" style="padding-left: 1rem;">
                                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-[#0F9D58] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full bg-slate-50 border border-slate-200 rounded-xl placeholder-slate-400 text-slate-900 focus:bg-white focus:outline-none focus:border-[#0F9D58] focus:ring-1 focus:ring-[#0F9D58] transition-all duration-300 sm:text-sm font-medium shadow-sm" placeholder="Password" style="padding-top: 0.875rem; padding-bottom: 0.875rem; padding-left: 3rem; padding-right: 3.5rem;">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1" style="margin-top: 1rem;">
                        <label class="flex items-center cursor-pointer select-none">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 bg-slate-50 text-[#0F9D58] focus:ring-[#0F9D58] border-slate-300 rounded cursor-pointer transition-colors">
                            <span class="ml-2 block text-sm text-slate-600 font-medium hover:text-slate-900 transition-colors">
                                Ingat sesi saya
                            </span>
                        </label>
                        <a href="backend/login.php" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition-colors">
                            Panel Backend &rarr;
                        </a>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <button type="submit" id="loginBtn" class="w-full flex justify-center items-center border border-transparent rounded-xl shadow-md text-sm font-bold text-white transition-all duration-300 uppercase tracking-widest bg-islami hover:opacity-90 cursor-pointer" style="padding-top: 1rem; padding-bottom: 1rem; padding-left: 1rem; padding-right: 1rem; background-color: #0F9D58; color: white;">
                            <span id="btnText">Secure Login</span>
                            <svg id="btnIcon" xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            <svg id="btnSpinner" class="animate-spin ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="text-center w-full text-[10px] font-bold text-slate-400 uppercase tracking-widest" style="margin-top: 2.5rem;">
                    <div class="mt-4 mb-3 text-center">
                        <a href="https://github.com/Forum-TIPD-PTKIN/sister-dosen-dashboard" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xl transition-all shadow-sm">
                            <svg height="16" width="16" viewBox="0 0 16 16" fill="currentColor"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
                            Download Source Code di GitHub
                        </a>
                    </div>
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($campusName) ?>
                </div>
            </div>
        </div>

        <!-- Right Side: Hero Image Area -->
        <div class="mobile-hide relative w-full h-full overflow-hidden bg-slate-900">
            <!-- Background Image -->
            <img src="sister_hero_banner.png" alt="SISTER Hero Banner" class="absolute inset-0 w-full h-full object-cover">
            
            <!-- Simple Dark Overlay to reduce glare without covering the existing text completely -->
            <div class="absolute inset-0 bg-slate-900/20 mix-blend-multiply"></div>

            <div class="absolute inset-0 flex flex-col justify-end p-12 text-white z-10 bg-gradient-to-t from-slate-950/85 via-slate-950/25 to-transparent">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 backdrop-blur-md text-emerald-300 text-xs font-semibold w-max mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Informasi Sumber Daya Terintegrasi
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight mb-2 drop-shadow-md">
                    SISTER Dosen Dashboard
                </h2>
                <p class="text-slate-200 text-sm max-w-lg leading-relaxed drop-shadow">
                    Platform analitik data dosen, pemantauan tridharma perguruan tinggi, dan integrasi resmi SISTER Web Service Kemdiktisaintek.
                </p>
            </div>
        </div>

    </div>

    <!-- Login Handler Script -->
    <script>
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('loginBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        const btnSpinner = document.getElementById('btnSpinner');
        const alertContainer = document.getElementById('alertContainer');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            btnText.textContent = 'Memverifikasi...';
            btnIcon.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
            btn.disabled = true;

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember_me').checked;

            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ username: email, password: password, remember: remember })
                });

                const data = await response.json();
                if (data.success) {
                    alertContainer.innerHTML = `
                        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <span>${data.message}</span>
                        </div>
                    `;
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.php';
                    }, 800);
                } else {
                    throw new Error(data.message || 'Login gagal.');
                }
            } catch (err) {
                alertContainer.innerHTML = `
                    <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <span>${err.message || 'Terjadi kesalahan sistem.'}</span>
                        </div>
                    </div>
                `;
                btnText.textContent = 'Secure Login';
                btnIcon.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
