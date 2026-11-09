<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];
$role = $data['role'] ?? 'public';

if ($role === 'admin') {
    $_SESSION['login'] = 1;
    $_SESSION['id_user'] = 10;
    $_SESSION['username'] = 'admin';
    $_SESSION['full_name'] = 'Super Administrator';
    $_SESSION['group_level'] = 'administrator';
    $_SESSION['sister_token'] = 'sister_admin_' . bin2hex(random_bytes(16));
    $_SESSION['token_expiry'] = time() + 7200;
    $_SESSION['user_role'] = 'Administrator SISTER';
    $_SESSION['user_id'] = 'admin';
    $_SESSION['login_time'] = time();

    echo json_encode([
        'success' => true,
        'message' => 'Login Super Admin berhasil! Mengalihkan ke Panel Admin...',
        'redirect' => 'backend/index.php'
    ]);
    exit;
}

// Default public/demo sister
$_SESSION['sister_token'] = 'demo_token_' . bin2hex(random_bytes(16));
$_SESSION['token_expiry'] = time() + 7200;
$_SESSION['user_role'] = 'Sister-WS Consumer Basic (Demo)';
$_SESSION['user_id'] = 'demo-user-sister';
$_SESSION['login_time'] = time();

echo json_encode([
    'success' => true,
    'message' => 'Login Demo berhasil! Mengalihkan ke dashboard...',
    'redirect' => 'index.php'
]);
