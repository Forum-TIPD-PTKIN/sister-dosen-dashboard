<?php
header("Access-Control-Allow-Origin: *");
include "inc/config.php";

$json_response = array();
$param = array();

$and_id_agama= "";
$and_nm_agama= "";

if ($_POST['nm_agama']!='all') {
//param here
$param = array(
    "id_agama" => $_POST["id_agama"],
	"nm_agama" => $_POST["nm_agama"]
);
    $and_id_agama = "and id_agama=?";
	$and_nm_agama = "and nm_agama=?";
}

if (isset($_GET["ask"])=="jumlah") {
    $total_tes = $db->fetchCustomSingle("SELECT count(*) as jumlah FROM agama  where 1=1 $and_id_agama $and_nm_agama",$param);
    if ($total_tes->jumlah>0) {
       $json_response['jumlah'] = $total_tes->jumlah;
    } else {
      $json_response['jumlah'] = 0;
    }
} else {
	$limit = 5;
	$offset = $_GET["offset"];
	$data_tes = $db->query("SELECT agama.id_agama,agama.nm_agama FROM agama  where 1=1 $and_id_agama $and_nm_agama limit $offset,$limit",$param);
	foreach ($data_tes as $key) {
	    $data_rec = array(
	        "id_agama" => $key->id_agama,
			"nm_agama" => $key->nm_agama
	    );
	    array_push($json_response, $data_rec);
	}
}

echo json_encode($json_response);