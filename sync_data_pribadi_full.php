<?php
// File: public/sync_data_pribadi_full.php
// Fungsi: Ambil data pribadi lengkap SDM dari API SISTER dan simpan ke database

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

function insertOrUpdateDataPribadi($db, $data) {
    $db->query(
        "REPLACE INTO data_pribadi (id_sdm, nik, nama, tempat_lahir, tanggal_lahir, id_jenis_kelamin, id_agama, id_status_perkawinan, nama_ibu_kandung, email, no_hp, alamat, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $data['id_sdm'] ?? null,
            $data['nik'] ?? null,
            $data['nama'] ?? null,
            $data['tempat_lahir'] ?? null,
            $data['tanggal_lahir'] ?? null,
            $data['id_jenis_kelamin'] ?? null,
            $data['id_agama'] ?? null,
            $data['id_status_perkawinan'] ?? null,
            $data['nama_ibu_kandung'] ?? null,
            $data['email'] ?? null,
            $data['no_hp'] ?? null,
            $data['alamat'] ?? null,
            json_encode($data, JSON_UNESCAPED_UNICODE)
        ]
    );
}

function syncDataPribadiFull($db, $api) {
    $sdmList = $api->getSDM();
    foreach ($sdmList as $sdm) {
        $id_sdm = $sdm['id'] ?? null;
        if (!$id_sdm) continue;
        $profil = $api->getDataPribadi($id_sdm, 'profil');
        $alamat = $api->getDataPribadi($id_sdm, 'alamat');
        $data = [
            'id_sdm' => $id_sdm,
            'nik' => $profil['nik'] ?? null,
            'nama' => $profil['nama'] ?? null,
            'tempat_lahir' => $profil['tempat_lahir'] ?? null,
            'tanggal_lahir' => $profil['tanggal_lahir'] ?? null,
            'id_jenis_kelamin' => $profil['id_jenis_kelamin'] ?? null,
            'id_agama' => $profil['id_agama'] ?? null,
            'id_status_perkawinan' => $profil['id_status_perkawinan'] ?? null,
            'nama_ibu_kandung' => $profil['nama_ibu_kandung'] ?? null,
            'email' => $alamat['email'] ?? null,
            'no_hp' => $alamat['no_hp'] ?? null,
            'alamat' => $alamat['alamat'] ?? null,
            'profil' => $profil,
            'alamat_detail' => $alamat
        ];
        insertOrUpdateDataPribadi($db, $data);
    }
    echo "Sync data pribadi lengkap completed.\n";
}

syncDataPribadiFull($db, $api);
?>
