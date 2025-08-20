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
        //get pendidikan
        $pendidikan = $api->getPendidikanFormal($key['id_sdm']);
        if (!empty($pendidikan)) {
            foreach ($pendidikan as $pend) {
                if (isset($pend['jenjang_pendidikan']) && $pend['jenjang_pendidikan']=='S1') {
                  $array_pendidikan = array(
                      'id' => $pend['id'] ?? null,
                      'jenjang_pendidikan' =>  $pend['jenjang_pendidikan'] ?? null,
                      'gelar_akademik' => $pend['gelar_akademik'] ?? null,
                      'bidang_studi' => $pend['bidang_studi'] ?? null,
                      'nama_perguruan_tinggi' => $pend['nama_perguruan_tinggi'] ?? null,
                      'tahun_lulus' => $pend['tahun_lulus'] ?? null,
                      'jenis_ajuan' => $pend['jenis_ajuan'] ?? null
                  );
                  $datas['s1'] = json_encode($array_pendidikan, JSON_UNESCAPED_UNICODE);
                } elseif (isset($pend['jenjang_pendidikan']) && $pend['jenjang_pendidikan']=='S2') {
                  $array_pendidikan = array(
                      'id' => $pend['id'] ?? null,
                      'jenjang_pendidikan' =>  $pend['jenjang_pendidikan'] ?? null,
                      'gelar_akademik' => $pend['gelar_akademik'] ?? null,
                      'bidang_studi' => $pend['bidang_studi'] ?? null,
                      'nama_perguruan_tinggi' => $pend['nama_perguruan_tinggi'] ?? null,
                      'tahun_lulus' => $pend['tahun_lulus'] ?? null,
                      'jenis_ajuan' => $pend['jenis_ajuan'] ?? null
                  );
                  $datas['s2'] = json_encode($array_pendidikan, JSON_UNESCAPED_UNICODE);
                } elseif (isset($pend['jenjang_pendidikan']) && $pend['jenjang_pendidikan']=='S3') {
                  $array_pendidikan = array(
                      'id' => $pend['id'] ?? null,
                      'jenjang_pendidikan' =>  $pend['jenjang_pendidikan'] ?? null,
                      'gelar_akademik' => $pend['gelar_akademik'] ?? null,
                      'bidang_studi' => $pend['bidang_studi'] ?? null,
                      'nama_perguruan_tinggi' => $pend['nama_perguruan_tinggi'] ?? null,
                      'tahun_lulus' => $pend['tahun_lulus'] ?? null,
                      'jenis_ajuan' => $pend['jenis_ajuan'] ?? null
                  );
                  $datas['s3'] = json_encode($array_pendidikan, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        $db->insert('tb_ref_sdm', $datas);
        exit();
        $data_insert[] = $datas;
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