<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

$json_response = array();
$param = array();

$and_id_semester= "";

if ($_POST['id_semester']!='all') {
  //param here
  $param = array(
    "id_semester" => $_POST["id_semester"]
  );

  $and_id_semester = "and id_semester=?";

}

if (isset($_GET["ask"])=="jumlah") {
    $total_krs = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM krs 
 inner JOIN semester ON krs.sem_id=semester.sem_id where 1=1 $and_id_semester",$param);
    if ($total_krs->jumlah>0) {
       $json_response['jumlah'] = $total_krs->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
	$limit = 50;
	$offset = $_GET["offset"];
	$data_krs = $db->query("SELECT krs.krs_id,krs.sem_id,krs.approve_ke,krs.mhs_id AS nim,krs.pengubah,krs.tgl_perubahan,semester.id_semester FROM krs 
 inner JOIN semester ON krs.sem_id=semester.sem_id where 1=1 $and_id_semester limit $offset,$limit",$param);
	foreach ($data_krs as $key) {
	    $data_rec = array(
	        "krs_id" => $key->krs_id,
					"sem_id" => $key->sem_id,
					"approve_ke" => $key->approve_ke,
					"nim" => $key->nim,
					"pengubah" => $key->pengubah,
					"tgl_perubahan" => $key->tgl_perubahan
	    );
	    array_push($json_response, $data_rec);
	}
}

echo json_encode($json_response);