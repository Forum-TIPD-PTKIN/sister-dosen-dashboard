<?php
// File: public/sync_data_pribadi.php
// Fungsi: Ambil data pribadi SDM dari API SISTER dan simpan ke database

require_once __DIR__ . '/backend/inc/config.php'; // DB config
require_once __DIR__ . '/backend/inc/lib/src/Backend/Database.php'; // DB helper
require_once 'includes/config.php'; // API config
require_once 'includes/SisterAPI.php';

use Backend\Database;

// Inisialisasi Database helper
$db = new Database($host, $port, $db_username, $db_password, $db_name);

// Inisialisasi API SISTER
$api = new SisterAPI();
$api->authenticate(); // Authenticate and set token

function insertOrUpdateDataPribadi($db, $sdm_id, $data) {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    $db->query(
        "REPLACE INTO data_pribadi (id_sdm, data) VALUES (?, ?)",
        [$sdm_id, $json]
    );
}

function syncDataPribadi($db, $api) {
    $sdmList = $api->getSDM();
    foreach ($sdmList as $sdm) {
        $id_sdm = $sdm['id'] ?? null;
        if (!$id_sdm) continue;
        $dataPribadi = $api->getDataPribadi($id_sdm);
        if ($dataPribadi) {
            insertOrUpdateDataPribadi($db, $id_sdm, $dataPribadi);
        }
    }
    echo "Sync data pribadi completed.\n";
}

syncDataPribadi($db, $api);
?>
