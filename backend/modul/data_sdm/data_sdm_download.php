<?php
//download data SDM from SISTER API and save to database
include "../../inc/config.php";
include "../../../includes/config.php";

$json_response = array();
$msg = "";
$values = "";

$data = $api->getReferensi('sdm');
$data_insert = array();
if (!empty($data)) {
    $db->query("TRUNCATE tb_ref_sdm");
    foreach ($data as $key) {
        $datas = array(
            'id_sdm' => $key['id_sdm'] ?? ($key['id'] ?? null),
            'nama_sdm' => (!empty($key['nama_sdm']) ? rtrim($key['nama_sdm']) : null),
            'nidn' => (!empty($key['nidn']) ? rtrim($key['nidn']) : null),
            'nip' => (!empty($key['nip']) ? rtrim($key['nip']) : null),
            'nuptk' => (!empty($key['nuptk']) ? rtrim($key['nuptk']) : null),
            'nama_status_aktif' => (!empty($key['nama_status_aktif']) ? rtrim($key['nama_status_aktif']) : null),
            'nama_status_pegawai' => (!empty($key['nama_status_pegawai']) ? rtrim($key['nama_status_pegawai']) : null),
            'jenis_sdm' => (!empty($key['jenis_sdm']) ? rtrim($key['jenis_sdm']) : null),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($datas['id_sdm'])) {
            $data_insert[] = $datas;
        }
    }
    if (!empty($data_insert)) {
        $insert = $db->insertMulti('tb_ref_sdm', $data_insert);
        if ($insert) {
            $json_response['success'] = true;
        } else {
            $json_response['success'] = false;
        }
    }
} else {
    $json_response['success'] = false;
    $json_response['message'] = "No data found.";
}

echo json_encode($json_response);
