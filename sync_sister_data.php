<?php
// File: public/sync_sister_data.php
// Fungsi: Ambil data dari API SISTER dan simpan ke database

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

// Helper untuk insert/update reference data
function insertOrUpdateReference($db, $type, $item) {
    $reference_id = $item['id'] ?? ($item['uuid'] ?? null);
    if (!$reference_id) return;
    $json = json_encode($item, JSON_UNESCAPED_UNICODE);
    $db->query(
        "REPLACE INTO reference_data (reference_type, reference_id, data) VALUES (?, ?, ?)",
        [$type, $reference_id, $json]
    );
}

// Sync reference data
function syncReference($db, $api, $type, $apiMethod) {
    if (is_callable($apiMethod)) {
        $data = $apiMethod($api);
    } else {
        $data = $api->$apiMethod();
    }
    foreach ($data as $item) {
        insertOrUpdateReference($db, $type, $item);
    }
}

// Sync SDM data
function syncSDM($db, $api) {
    $sdmList = $api->getSDM();
    foreach ($sdmList as $sdm) {
        $id_sdm = $sdm['id'] ?? null;
        if (!$id_sdm) continue;
        $db->query(
            "REPLACE INTO data_pribadi (id_sdm, nik, nama, tempat_lahir, tanggal_lahir, id_jenis_kelamin, id_agama, id_status_perkawinan, nama_ibu_kandung, email, no_hp, alamat, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $id_sdm,
                $sdm['nik'] ?? '',
                $sdm['nama_sdm'] ?? '',
                $sdm['tempat_lahir'] ?? '',
                $sdm['tanggal_lahir'] ?? null,
                $sdm['id_jenis_kelamin'] ?? '',
                $sdm['id_agama'] ?? '',
                $sdm['id_status_perkawinan'] ?? '',
                $sdm['nama_ibu_kandung'] ?? '',
                $sdm['email'] ?? '',
                $sdm['no_hp'] ?? '',
                $sdm['alamat'] ?? '',
                json_encode($sdm, JSON_UNESCAPED_UNICODE)
            ]
        );
    }
}

// Sync penugasan data
function syncPenugasan($db, $api) {
    $penugasanList = $api->getReferensi('penugasan');
    foreach ($penugasanList as $p) {
        $id = $p['id'] ?? null;
        if (!$id) continue;
        $db->query(
            "REPLACE INTO penugasan (id, id_sdm, id_ikatan_kerja, id_unit_kerja, id_perguruan_tinggi, tanggal_mulai, tanggal_selesai, no_sk_penugasan, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $id,
                $p['id_sdm'] ?? '',
                $p['id_ikatan_kerja'] ?? '',
                $p['id_unit_kerja'] ?? '',
                $p['id_perguruan_tinggi'] ?? '',
                $p['tanggal_mulai'] ?? null,
                $p['tanggal_selesai'] ?? null,
                $p['no_sk_penugasan'] ?? '',
                json_encode($p, JSON_UNESCAPED_UNICODE)
            ]
        );
    }
}

// Sync pendidikan formal data
function syncPendidikanFormal($db, $api) {
    $pendList = $api->getPendidikanFormal();
    foreach ($pendList as $p) {
        $id = $p['id'] ?? null;
        if (!$id) continue;
        $db->query(
            "REPLACE INTO pendidikan_formal (id, id_sdm, id_perguruan_tinggi, id_bidang_studi, id_jenjang_pendidikan, gelar_akademik, tanggal_lulus, nim, no_ijazah, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $id,
                $p['id_sdm'] ?? '',
                $p['id_perguruan_tinggi'] ?? '',
                $p['id_bidang_studi'] ?? '',
                $p['id_jenjang_pendidikan'] ?? '',
                $p['gelar_akademik'] ?? '',
                $p['tanggal_lulus'] ?? null,
                $p['nim'] ?? '',
                $p['no_ijazah'] ?? '',
                json_encode($p, JSON_UNESCAPED_UNICODE)
            ]
        );
    }
}

// Jalankan sinkronisasi
function main() {
    global $db, $api;
    // Reference endpoints
    $reference_types = [
        'agama',
        'bidang_studi',
        'bidang_usaha',
        'detail_unit_kerja',
        //'dudi',
        'gelar_akademik',
        'golongan_pangkat',
        'ikatan_kerja',
        'jabatan_fungsional',
        'jabatan_negara',
        'jabatan_tugas_tambahan',
        'jenis_bahan_ajar',
        'jenis_beasiswa',
        'jenis_diklat',
        'jenis_dokumen',
        'jenis_keluar',
        'jenis_kepanitiaan',
        'jenis_kesejahteraan',
        'jenis_pekerjaan',
        'jenis_penghargaan',
        'jenis_publikasi',
        'jenis_tes',
        'jenis_tunjangan',
        'jenjang_pendidikan',
        'kategori_capaian_luaran',
        'kategori_kegiatan',
        'kelompok_bidang',
        'lembaga_sertifikasi',
        'mahasiswa_pddikti',
        'media_publikasi',
        'negara',
        'perguruan_tinggi',
        'profil_pt',
        'sdm',
        'semester',
        'skim_kegiatan',
        'status_kepegawaian',
        'sumber_gaji',
        'tingkat_penghargaan',
        'unit_kerja',
        'wilayah'
    ];
    foreach ($reference_types as $type) {
        syncReference($db, $api, $type, function($api) use ($type) { return $api->getReferensi($type); });
    }
    // SDM
    syncSDM($db, $api);
    // Penugasan
    syncPenugasan($db, $api);
    // Pendidikan Formal
    syncPendidikanFormal($db, $api);
    // Tambah endpoint lain sesuai kebutuhan
    echo "Sync completed.\n";
}

main();
?>
