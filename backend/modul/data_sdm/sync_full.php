<?php
session_start();
header('Content-Type: application/json');

set_time_limit(0);

require_once __DIR__ . '/../../../sync_sister_data.php';

try {
    $stats = runSisterDashboardSync($db, $api);

    echo json_encode([
        'success' => true,
        'message' => 'Sinkronisasi selesai.',
        'data' => $stats,
    ]);
} catch (Throwable $exception) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage(),
    ]);
}
