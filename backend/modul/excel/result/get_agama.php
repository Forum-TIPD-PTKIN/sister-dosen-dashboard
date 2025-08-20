<?php
header("Access-Control-Allow-Origin: *");
include "admina/inc/config.php";

$param = array();

if ($_POST['kode_jur']!='all') {
  //param here
  $param = array("id_semester" => $_POST["id_semester"]);

  $and_id_semester = "and id_semester=?";

}



$json_response = array();

$temp_rec = $db->query("SELECT krs.krs_id,krs.sem_id,krs.approve_ke,krs.mhs_id,krs.pengubah,krs.tgl_perubahan,semester.id_semester FROM krs 
 inner JOIN semester ON krs.sem_id=semester.sem_idwhere 1=1 $and_id_semester limit $offset,$limit",array("id_semester" => $_POST["id_semester"])
    );

if (isset($_GET["ask"])=="jumlah") {
    $total_agama = $db->fetchCustomSingle("where 1=1 $and_id_semester",$param);
    if ($total_agama->jumlah>0) {
       $json_response['jumlah'] = $total_agama->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
	$limit = 50;
	$offset = $_GET["offset"];
	foreach ($temp_rec as $key) {
	    $data_rec = array(
	          "krs_id" => $key->krs_id,
					"sem_id" => $key->sem_id,
					"approve_ke" => $key->approve_ke,
					"mhs_id" => $key->mhs_id,
					"pengubah" => $key->pengubah,
					"tgl_perubahan" => $key->tgl_perubahan,
					"id_semester" => $key->id_semester
	        );
	    array_push($json_response, $data_rec);
	}
}

echo json_encode($json_response);