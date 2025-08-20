<?php
// Usage: php grab_pengabdian.php
// This script will grab pengabdian data for all SDM using SisterAPI and config.php

require_once __DIR__ . '/backend/inc/config.php';
require_once __DIR__ . '/includes/config.php';


// Get all id_sdm from tb_ref_sdm
$sdm_result = $db->query("SELECT id_sdm FROM tb_ref_sdm");
if ($sdm_result->rowCount() < 1) {
    echo "No SDM found in tb_ref_sdm.\n";
    exit(1);
}

$total_inserted = 0;
$total_sdm = $sdm_result->rowCount();
if ($total_sdm < 1) {
    echo "No SDM records to process.\n";
    exit(1);
}
foreach ($sdm_result as $sdm_row) {
    $id_sdm = $sdm_row->id_sdm;
    $pengabdian = $api->getPengabdian($id_sdm);
    if (!is_array($pengabdian) || count($pengabdian) < 1) {
        echo "No pengabdian data for id_sdm $id_sdm.\n";
        continue;
    }

    $inserted = 0;
    foreach ($pengabdian as $row) {
        $id_pengabdian = $row['id'] ?? '';
        $judul = $row['judul'] ?? '';
        $bidang_keilmuan = isset($row['bidang_keilmuan']) ? json_encode($row['bidang_keilmuan'], JSON_UNESCAPED_UNICODE) : null;
        $tahun_pelaksanaan = $row['tahun_pelaksanaan'] ?? null;
        $lama_kegiatan = $row['lama_kegiatan'] ?? 0;

        $sql = "INSERT INTO tb_data_pengabdian (id, judul, id_sdm, bidang_keilmuan, tahun_pelaksanaan, lama_kegiatan) VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$id_pengabdian, $judul, $id_sdm, $bidang_keilmuan, $tahun_pelaksanaan, $lama_kegiatan];
        $db->query($sql, $params);
        $inserted++;
    }
    $total_inserted += $inserted;
    echo "id_sdm $id_sdm: Inserted $inserted records.\n";
}

echo "Total SDM processed: $total_sdm\n";
echo "Total pengabdian records inserted: $total_inserted\n";
