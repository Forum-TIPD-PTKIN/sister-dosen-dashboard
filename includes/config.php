<?php
include "SisterAPI.php";

// Configuration file for SISTER API Dashboard

// API Configuration
define('SISTER_API_BASE_URL', 'https://sister-api.kemdikbud.go.id/ws.php/1.0');
define('SISTER_API_SANDBOX_URL', 'https://sister-api.kemdikbud.go.id/ws-sandbox.php/1.0');

// Credentials - Updated with working credentials from Postman
define('SISTER_USER_ID', 'b7071d0c-d379-4493-ac86-18fcc259d913');
define('SISTER_USERNAME', '5uh431uFPvlMKcsReUvWrtVs7l6c+m8GFmZHcR7qdPznmXJI8ZSTnxMOESEKt2py1ayuTg4qvjdGSt9F7Ecbik6kSzBjLGiffIbzD7b2LGw=');
define('SISTER_PASSWORD', 'AFRfk2FZWQAxDSC+GYCHbpr2cpFj0IDNni2o75Ac/pt5qeTpsvTqatTs13RXya0y8DwV4OcV1LdTfP1G+bQMDh8aafaOoOCUXnwQINud18uEa7mhZXemH8faXyOAGVqG');
define('SISTER_ROLE', 'Sister-WS Basic');

// Application Settings
define('TOKEN_LIFETIME', 3600); // 1 hour in seconds
define('MAX_RETRIES', 3);
define('REQUEST_TIMEOUT', 30);
define('AUTO_REFRESH_INTERVAL', 300); // 5 minutes

//googel cse
$api_key = 'AIzaSyD3fpYUnVbOvYy0xYFPQvJB0-Wo-HQxXLQ';
$cse_id = '75f0e3ac3ab3c479f';

// Chart colors
$CHART_COLORS = [
    'primary' => '#4e73df',
    'success' => '#1cc88a',
    'info' => '#36b9cc',
    'warning' => '#f6c23e',
    'danger' => '#e74a3b',
    'secondary' => '#858796',
    'light' => '#f8f9fc',
    'dark' => '#5a5c69'
];


// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Inisialisasi API SISTER
$api = new SisterAPI();
$api->authenticate(); // Authenticate and set token

// Session configuration
/*ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));*/
?>
