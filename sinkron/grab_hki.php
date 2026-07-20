<?php
// Usage: php grab_hki.php
// This script will grab kekayaan intelektual (HKI) data for all SDM using SisterAPI and config.php

require_once __DIR__ . '/../backend/inc/config.php';
require_once __DIR__ . '/../includes/config.php';

if (!isset($api) || !$api instanceof SisterAPI) {
    $api = new SisterAPI();
    $api->authenticate();
}

// Get all id_sdm from tb_ref_sdm
$sdm_result = $db->query("SELECT id_sdm FROM tb_ref_sdm");
$sdm_rows = [];
foreach ($sdm_result as $row) {
    $sdm_rows[] = $row;
}
if (count($sdm_rows) < 1) {
    echo "No SDM found in tb_ref_sdm.\n";
    exit(1);
}

$total_inserted = 0;
$total_sdm = count($sdm_rows);
if ($total_sdm < 1) {
    echo "No SDM records to process.\n";
    exit(1);
}
foreach ($sdm_rows as $sdm_row) {
    $id_sdm = $sdm_row->id_sdm;
    $hki = $api->getKekayaanIntelektual($id_sdm);
    if (!is_array($hki) || count($hki) < 1) {
        echo "No HKI data for id_sdm $id_sdm.\n";
        continue;
    }

    $inserted = 0;
    $db->query("DELETE FROM tb_data_hki WHERE id_sdm = ?", [$id_sdm]);
    foreach ($hki as $row) {
        $id_hki = $row['id'] ?? '';
        $kategori_kegiatan = $row['kategori_kegiatan'] ?? '';
        $judul = $row['judul'] ?? '';
        $quartile = $row['quartile'] ?? null;
        $bidang_keilmuan = isset($row['bidang_keilmuan']) ? json_encode($row['bidang_keilmuan'], JSON_UNESCAPED_UNICODE) : null;
        $jenis_publikasi = $row['jenis_publikasi'] ?? '';
        $tanggal = $row['tanggal'] ?? null;

        $sql = "INSERT INTO tb_data_hki (id, id_sdm, kategori_kegiatan, judul, quartile, bidang_keilmuan, jenis_publikasi, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$id_hki, $id_sdm, $kategori_kegiatan, $judul, $quartile, $bidang_keilmuan, $jenis_publikasi, $tanggal];
        $db->query($sql, $params);
        $inserted++;
    }
    $total_inserted += $inserted;
    echo "id_sdm $id_sdm: Inserted $inserted records.\n";
}

echo "Total SDM processed: $total_sdm\n";
echo "Total HKI records inserted: $total_inserted\n";
