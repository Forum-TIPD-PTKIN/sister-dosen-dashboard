<?php
session_start();
header('Access-Control-Allow-Origin: *');
include "../../inc/config.php";
include "../../../includes/config.php";

$json_response = array();
$msg = "";
$values = "";

$data = $api->getReferensi('sdm');
$data_insert = array();

$json_response = array();

if (!empty($data)) {
    //$db->query("TRUNCATE tb_ref_sdm");
     $db->query("TRUNCATE tb_ref_sdm_pendidikan");
      $db->query("TRUNCATE tb_ref_sdm_penugasan");
    $db->query("TRUNCATE tb_ref_sdm_jabatan_fungsional");
    foreach ($data as $key) {
        $datas[] = array(
        'id_sdm' => $key['id_sdm'] ?? null,
        'nama_sdm' => ($key['nama_sdm']!='' ? rtrim($key['nama_sdm'] ?? null) : null),
        'nidn' => ($key['nidn']!='' ? rtrim($key['nidn'] ?? null) : null),
        'nip' => ($key['nip']!='' ? rtrim($key['nip'] ?? null) : null),
        'nuptk' => ($key['nuptk']!='' ? rtrim($key['nuptk'] ?? null) : null),
        'nama_status_aktif' => ($key['nama_status_aktif']!='' ? rtrim($key['nama_status_aktif'] ?? null) : null),
        'nama_status_pegawai' => ($key['nama_status_pegawai']!='' ? rtrim($key['nama_status_pegawai'] ?? null) : null),
        'jenis_sdm' => ($key['jenis_sdm']!='' ? rtrim($key['jenis_sdm'] ?? null) : null),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
        );
    }
    $json_response['jumlah'] = count($datas);
   // $insert = $db->insertMulti('tb_ref_sdm', $datas);
} else {
    $json_response['jumlah'] = 0;
}

echo json_encode($json_response);