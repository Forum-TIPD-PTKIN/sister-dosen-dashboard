<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

$json_response = array();
$param = array();



if (isset($_GET["ask"])=="jumlah") {
    $total_kelas = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM kelas ",$param);
    if ($total_kelas->jumlah>0) {
       $json_response['jumlah'] = $total_kelas->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
	$limit = 50;
	$offset = $_GET["offset"];
	$data_kelas = $db->query("SELECT kelas.kelas_id,kelas.sem_id,kelas.id_matkul,kelas.id_matkul_setara,kelas.kls_nama,kelas.peserta_max,kelas.peserta_min,kelas.id_jenis_kelas,kelas.is_open,kelas.catatan FROM kelas  limit $offset,$limit",$param);
	foreach ($data_kelas as $key) {
	    $data_rec = array(
	        "kelas_id" => $key->kelas_id,
					"sem_id" => $key->sem_id,
					"id_matkul" => $key->id_matkul,
					"id_matkul_setara" => $key->id_matkul_setara,
					"kls_nama" => $key->kls_nama,
					"peserta_max" => $key->peserta_max,
					"peserta_min" => $key->peserta_min,
					"id_jenis_kelas" => $key->id_jenis_kelas,
					"is_open" => $key->is_open,
					"catatan" => $key->catatan
	    );
	    array_push($json_response, $data_rec);
	}
}

echo json_encode($json_response);