<?php
header("Access-Control-Allow-Origin: *");
include "admina/inc/config.php";

$offset = $_GET["offset"];
$limit = 50;

$jur_kode = $_POST["jur_kode"];


$json_response = array();

$temp_rec = $db->query("SELECT mahasiswa.nim,mahasiswa.nama AS nama_mahasiswa,mahasiswa.jur_kode,view_prodi_jenjang.jurusan FROM mahasiswa 
 inner JOIN view_prodi_jenjang ON mahasiswa.jur_kode=view_prodi_jenjang.kode_jurwhere jur_kode=? limit $offset,$limit",array("jur_kode" => $jur_kode)
    );

foreach ($temp_rec as $key) {
    $data_rec = array(
          "nim" => $key->nim,
					"nama_mahasiswa" => $key->nama_mahasiswa,
					"jur_kode" => $key->jur_kode,
					"jurusan" => $key->jurusan
        );
    array_push($json_response, $data_rec);
}

echo json_encode($json_response);