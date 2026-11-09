<?php
session_start();

// Hapus semua data sesi
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_unset();
session_destroy();

// Cek apakah request meminta JSON / AJAX
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
          (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
          ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Logout berhasil',
        'redirect' => 'login.php',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Jika diakses langsung via browser GET, redirect ke login.php
header('Location: ../login.php');
exit;
