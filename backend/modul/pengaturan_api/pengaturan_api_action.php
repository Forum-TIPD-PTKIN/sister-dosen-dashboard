<?php
session_start();
header('Content-Type: application/json');

require_once "../../inc/config.php";

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi login telah berakhir.']);
    exit;
}

$act = $_GET['act'] ?? '';

switch ($act) {
    case 'save':
        $mode = $_POST['mode'] ?? 'production';
        $api_base_url = trim($_POST['api_base_url'] ?? '');
        $api_sandbox_url = trim($_POST['api_sandbox_url'] ?? '');
        $user_id = trim($_POST['user_id'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'Sister-WS Consumer Basic');
        $token_lifetime = (int)($_POST['token_lifetime'] ?? 3600);

        if (empty($api_base_url) || empty($user_id) || empty($username)) {
            echo json_encode(['status' => 'error', 'message' => 'Base URL, User ID, dan Username wajib diisi!']);
            exit;
        }

        $data = [
            'mode' => $mode,
            'api_base_url' => $api_base_url,
            'api_sandbox_url' => $api_sandbox_url,
            'user_id' => $user_id,
            'username' => $username,
            'role' => $role,
            'token_lifetime' => $token_lifetime
        ];

        // Jika password diisi, update password
        if (!empty($password)) {
            $data['password'] = $password;
        }

        $check = $db->fetchSingleRow("tb_pengaturan_api", "id", 1);
        if ($check) {
            $db->update("tb_pengaturan_api", $data, "id", 1);
        } else {
            $data['id'] = 1;
            $db->insert("tb_pengaturan_api", $data);
        }

        echo json_encode(['status' => 'success', 'message' => 'Pengaturan API SISTER berhasil disimpan ke database.']);
        break;

    case 'test':
        require_once "../../../includes/config.php";

        $setting = $db->fetchSingleRow("tb_pengaturan_api", "id", 1);
        if (!$setting || empty($setting->username) || empty($setting->password) || empty($setting->user_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Kredensial SISTER belum lengkap disimpan di database.']);
            exit;
        }

        $targetUrl = ($setting->mode === 'sandbox' && !empty($setting->api_sandbox_url)) ? $setting->api_sandbox_url : $setting->api_base_url;

        // Inisialisasi request uji coba langsung
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => rtrim($targetUrl, '/') . '/authorize',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'username' => $setting->username,
                'password' => $setting->password,
                'id_pengguna' => $setting->user_id
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghubungi server SISTER: ' . $err]);
            exit;
        }

        $body = json_decode($res, true);
        if ($httpCode >= 200 && $httpCode < 300 && isset($body['token'])) {
            $tokenPreview = substr($body['token'], 0, 10) . '...' . substr($body['token'], -8);
            echo json_encode([
                'status' => 'success',
                'message' => 'Autentikasi ke SISTER (' . strtoupper($setting->mode) . ') berhasil!',
                'token_preview' => $tokenPreview
            ]);
        } else {
            $errMsg = $body['message'] ?? ($body['error'] ?? 'HTTP Code ' . $httpCode . ' - Gagal autentikasi ke server SISTER.');
            echo json_encode(['status' => 'error', 'message' => $errMsg]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
        break;
}
