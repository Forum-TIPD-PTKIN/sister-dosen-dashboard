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


$reference_types = [
        'agama',
        'bidang_studi',
        'unit_kerja',
        'bidang_usaha',
        //'detail_unit_kerja',
        'dudi',
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
       // 'kategori_kegiatan',
        'kelompok_bidang',
        'lembaga_sertifikasi',
        //'mahasiswa_pddikti',
        'media_publikasi',
        //'negara',
        //'perguruan_tinggi',
        //'profil_pt',
        'sdm',
        'semester',
        'skim_kegiatan',
        'status_kepegawaian',
        'sumber_gaji',
        'tingkat_penghargaan',
       
        //'wilayah'
    ];

$type = $reference_types[$offset];

$param = array();
  if($type=='unit_kerja') {
    $param = array('id_perguruan_tinggi' => '8f3e7954-2f89-44d9-9c9e-37b59b0d5e15');
  }

$data = $api->getReferensi($type,$param);
$data_rec = array();

 $table = 'tb_reference_data';

if(!empty($data)) {
    $insert_reference = array();
    $update_reference = array();
    $reference_id = array();
                foreach ($data as $key) {
                   
                    if($type=='unit_kerja') {
                        $table = 'tb_ref_unit';
                            $insert_reference[] = array(
                                'id' => $key['id'],
                                'nama' => $key['nama'],
                                'id_jenis_unit' => $key['id_jenis_unit'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                    } elseif ($type == 'sdm') {
                         $table = 'tb_ref_sdm';
                            $insert_reference[] = array(
                                'id_sdm' => $key['id_sdm'],
                                'nama_sdm' => $key['nama_sdm'],
                                'nidn' => $key['nidn'],
                                'nip' => $key['nip'],
                                'nuptk' => $key['nuptk'],
                                'nama_status_aktif' => $key['nama_status_aktif'],
                                'nama_status_pegawai' => $key['nama_status_pegawai'],
                                'jenis_sdm' => $key['jenis_sdm'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                    } else {
                        $insert_reference[] = array(
                            'reference_type' => $type,
                            'reference_id' => $key['id'],
                            'data' => json_encode($key, JSON_UNESCAPED_UNICODE),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        );
                    }

                            /*
                        $check = $db->checkExist('reference_data',array('reference_id' => $key['id']));
                        if ($check==true) {
                            $update_reference[] = array(
                                'reference_type' => $type,
                                'reference_id' => $key['id'],
                                'data' => json_encode($key, JSON_UNESCAPED_UNICODE),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $reference_id[] = $key['id'];
                        } else {
                            $insert_reference[] = array(
                                'reference_type' => $type,
                                'reference_id' => $key['id'],
                                'data' => json_encode($key, JSON_UNESCAPED_UNICODE),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                        }
                            */
                    }
                 
                if (!empty($update_reference)) {
                    $db->updateMulti('reference_data',$update_reference,'reference_id',$reference_id);
                }


                if (!empty($insert_reference)) {
                  //  dump($insert_reference);
                  $db->insertMulti($table,$insert_reference);
                  echo $db->getErrorMessage();
                }
}



if ($_POST['last']=='yes') {
    //echo "<pre>";

$msg =  "<div class=\"alert alert-warning \" role=\"alert\">
        <font color=\"#3c763d\">".$_POST['total_data']." data update dari portal DataMahasiswa berhasil di Unduh</font><br />";
        $msg .= "</div>
        </div>";

        $jumlah['last_notif'] = $msg;
}



array_push($json_response, $jumlah);

echo json_encode($json_response);
exit();
?>