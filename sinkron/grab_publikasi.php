<?php
// Usage: php grab_publikasi.php
// This script will grab publikasi data for all SDM using SisterAPI and config.php

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
    $publikasi = $api->getPublikasi($id_sdm);
    if (!is_array($publikasi) || count($publikasi) < 1) {
        echo "No publikasi data for id_sdm $id_sdm.\n";
        continue;
    }

    $inserted = 0;
    $db->query("DELETE FROM tb_data_publikasi WHERE id_sdm = ?", [$id_sdm]);
    foreach ($publikasi as $row) {
        $id_kategori_kegiatan = $row['id_kategori_kegiatan'] ?? 0;
        $a_klaim_bkd = isset($row['a_klaim_bkd']) ? (int)$row['a_klaim_bkd'] : 0;
        $wkt_klaim_bkd = $row['wkt_klaim_bkd'] ?? null;
        $id_publikasi = $row['id'] ?? '';
        $judul = $row['judul'] ?? '';
        $quartile = $row['quartile'] ?? null;
        $jenis_publikasi = $row['jenis_publikasi'] ?? '';
        $tanggal = $row['tanggal'] ?? null;
        $kategori_kegiatan = $row['kategori_kegiatan'] ?? '';
        $asal_data = $row['asal_data'] ?? '';
        $bidang_keilmuan = isset($row['bidang_keilmuan']) ? json_encode($row['bidang_keilmuan'], JSON_UNESCAPED_UNICODE) : null;

        $sql = "INSERT INTO tb_data_publikasi (id_kategori_kegiatan, id_sdm, a_klaim_bkd, wkt_klaim_bkd, judul, quartile, jenis_publikasi, tanggal, kategori_kegiatan, asal_data, bidang_keilmuan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$id_kategori_kegiatan, $id_sdm, $a_klaim_bkd, $wkt_klaim_bkd, $judul, $quartile, $jenis_publikasi, $tanggal, $kategori_kegiatan, $asal_data, $bidang_keilmuan];
        $db->query($sql, $params);
        $inserted++;
    }
    $total_inserted += $inserted;
    echo "id_sdm $id_sdm: Inserted $inserted records.\n";
}

echo "Total SDM processed: $total_sdm\n";
echo "Total publikasi records inserted: $total_inserted\n";
