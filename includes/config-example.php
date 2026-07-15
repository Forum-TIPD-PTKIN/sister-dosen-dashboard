<?php

require_once __DIR__ . '/SisterAPI.php';

// Database configuration used by dashboard-related code.
define('DB_HOST', 'localhost');
define('DB_NAME', 'sister_api');
define('DB_USER', 'sister_app');
define('DB_PASS', '');

// Official SISTER Web Service endpoints.
define('SISTER_API_BASE_URL', 'https://sister-api.kemdiktisaintek.go.id/ws.php/1.0');
define('SISTER_API_SANDBOX_URL', 'https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0');

// Fill these values locally. Never commit real credentials.
define('SISTER_USER_ID', '');
define('SISTER_USERNAME', '');
define('SISTER_PASSWORD', '');
define('SISTER_ROLE', '');

// Application settings.
define('TOKEN_LIFETIME', 3600);
define('MAX_RETRIES', 3);
define('REQUEST_TIMEOUT', 30);
define('AUTO_REFRESH_INTERVAL', 300);

// Optional Google Custom Search configuration.
$api_key = '';
$cse_id = '';

$CHART_COLORS = [
    'primary' => '#4e73df',
    'success' => '#1cc88a',
    'info' => '#36b9cc',
    'warning' => '#f6c23e',
    'danger' => '#e74a3b',
    'secondary' => '#858796',
    'light' => '#f8f9fc',
    'dark' => '#5a5c69',
];

date_default_timezone_set('Asia/Jakarta');

// Authentication runs when this configuration is loaded.
$api = new SisterAPI();
$api->authenticate();
