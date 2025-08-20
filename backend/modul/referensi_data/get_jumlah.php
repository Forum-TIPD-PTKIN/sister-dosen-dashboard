<?php
session_start();
header('Access-Control-Allow-Origin: *');
include "../../inc/config.php";
$json_response = array();
 $reference_types = [
        'agama',
        'bidang_studi',
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
        //'kategori_kegiatan',
        'kelompok_bidang',
        'lembaga_sertifikasi',
       // 'mahasiswa_pddikti',
        'media_publikasi',
       // 'negara',
        //'perguruan_tinggi',
        //'profil_pt',
        'sdm',
        'semester',
        'skim_kegiatan',
        'status_kepegawaian',
        'sumber_gaji',
        'tingkat_penghargaan',
        'unit_kerja',
        //'wilayah'
    ];
$db->query("TRUNCATE TABLE tb_reference_data");
$db->query("TRUNCATE TABLE tb_ref_sdm");
$db->query("TRUNCATE TABLE tb_ref_unit");
$json_response['jumlah'] = count($reference_types);

echo json_encode($json_response);