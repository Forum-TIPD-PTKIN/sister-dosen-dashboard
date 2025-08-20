<?php
include "../../inc/config.php";
include "../../../includes/config.php";

$json_response = array();
$msg = "";
$values = "";


if ($_POST['total_data']<1) {
    $msg =  "<div class=\"alert alert-warning \" role=\"alert\">
        <font color=\"#3c763d\">Tidak ada data berhasil di Upload</font><br />
        </div>";

    $jumlah['last_notif'] = $msg;
    array_push($json_response, $jumlah);

    echo json_encode($json_response);
    exit();
}


$offset = $_POST['offset'];

$jumlah['offset'] = $offset;
$data_insert = array();
$datas_penugasan = array();
$datas_jabatan_fungsional = array();
$data_sdm = $db->query("select * from tb_ref_sdm order by id_sdm asc limit $offset,5");
foreach ($data_sdm as $value) {
    //get pendidikan
        $pendidikan = $api->getPendidikanFormal($value->id_sdm);
        $penugasan = $api->getPenugasan($value->id_sdm);
        $jabatan_fungsional = $api->getJabatanFungsional($value->id_sdm);
        if (!empty($penugasan)) {
            foreach ($penugasan as $pen) {
                if($pen['apakah_penugasan_homebase'] == 'Ya') {
                    $datas_penugasan[] = array(
                        'id_sdm' => $value->id_sdm,
                        'unit_kerja' => 'Program Studi ' . rtrim($pen['jenjang_pendidikan']) . ' ' . rtrim($pen['unit_kerja']),
                    );
                }
            }
        }
        if (!empty($jabatan_fungsional)) {
            foreach ($jabatan_fungsional as $jab) {
                $datas_jabatan_fungsional[] = array(
                    'id_sdm' => $value->id_sdm,
                    'jabatan_fungsional' => rtrim($jab['jabatan_fungsional']),
                    'sk' => $jab['sk'] ?? '',
                    'tanggal_mulai' => $jab['tanggal_mulai'] ?? null,
                );
            }
        }
        if (!empty($pendidikan)) {
            foreach ($pendidikan as $pend) {
              $datas = array(
               
              );
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
                  $datas['jenjang_pendidikan'] = 'S1';
                  $datas['datas'] = json_encode($array_pendidikan, JSON_UNESCAPED_UNICODE);
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
                  $datas['jenjang_pendidikan'] = 'S2';
                  $datas['datas'] = json_encode($array_pendidikan, JSON_UNESCAPED_UNICODE);
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
                  $datas['jenjang_pendidikan'] = 'S3';
                  $datas['datas'] = json_encode($array_pendidikan, JSON_UNESCAPED_UNICODE);
                }
                if(!empty($datas)) {
                    $datas['id_sdm'] = $value->id_sdm;
                     $data_insert[] = $datas;
                }
               
            }
        }

       
}

if (!empty($datas_penugasan)) {
    $db->insertMulti('tb_ref_sdm_penugasan', $datas_penugasan);
}

if (!empty($datas_jabatan_fungsional)) {
    $db->insertMulti('tb_ref_sdm_jabatan_fungsional', $datas_jabatan_fungsional);
}

if (!empty($data_insert)) {
    $db->insertMulti('tb_ref_sdm_pendidikan', $data_insert);
}
echo $db->getErrorMessage();
if ($_POST['last']=='yes') {
    //echo "<pre>";

$msg =  "<div class=\"alert alert-warning \" role=\"alert\">
        <font color=\"#3c763d\">".$_POST['total_data']." data Berhasil di Unduh</font><br />";
        $msg .= "</div>
        </div>";

        $jumlah['last_notif'] = $msg;
}



array_push($json_response, $jumlah);

echo json_encode($json_response);
exit();
?>