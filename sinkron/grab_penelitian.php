<?php
// Usage: php grab_penelitian.php
// This script will grab penelitian data for all SDM using SisterAPI and config.php

require_once __DIR__ . '/../backend/inc/config.php';
require_once __DIR__ . '/../includes/config.php';

if (!isset($api) || !$api instanceof SisterAPI) {
    $api = new SisterAPI();
    $api->authenticate();
}

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
    $penelitian = $api->getPenelitian($id_sdm);
    if (!is_array($penelitian) || count($penelitian) < 1) {
        echo "No penelitian data for id_sdm $id_sdm.\n";
        continue;
    }

    $inserted = 0;
    $db->query("DELETE FROM tb_data_penelitian WHERE id_sdm = ?", [$id_sdm]);
    foreach ($penelitian as $row) {
        $id_penelitian = $row['id'] ?? '';
        $judul = $row['judul'] ?? '';
        $bidang_keilmuan = isset($row['bidang_keilmuan']) ? json_encode($row['bidang_keilmuan'], JSON_UNESCAPED_UNICODE) : null;
        $tahun_pelaksanaan = $row['tahun_pelaksanaan'] ?? null;
        $lama_kegiatan = $row['lama_kegiatan'] ?? 0;

        $sql = "INSERT INTO tb_data_penelitian (id_penelitian, id_sdm, judul, bidang_keilmuan, tahun_pelaksanaan, lama_kegiatan) VALUES (?, ?, ?, ?, ?, ?)";
        $params = [$id_penelitian, $id_sdm, $judul, $bidang_keilmuan, $tahun_pelaksanaan, $lama_kegiatan];
        $db->query($sql, $params);
        $inserted++;
    }
    $total_inserted += $inserted;
    echo "id_sdm $id_sdm: Inserted $inserted records.\n";
}

echo "Total SDM processed: $total_sdm\n";
echo "Total penelitian records inserted: $total_inserted\n";
