<?php
session_start();
include "../../inc/config.php";
$arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
stream_context_set_default($arrContextOptions);

$columns = array(
    'tb_master_pengaturan_umum.nama_pengaturan',
    'tb_master_pengaturan_umum.isi_pengaturan',
    'tb_master_pengaturan_umum.id_pengaturan',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_master_pengaturan_umum.isi_pengaturan","tb_master_pengaturan_umum.id_pengaturan");
  
        //set numbering is true
        $datatable->setNumberingStatus(0);

  //set order by column
  $datatable->setOrderBy("tb_master_pengaturan_umum.id_pengaturan asc");


  //set group by column
  //$datatable->setGroupBy("tb_master_pengaturan_umum.id_pengaturan");

  $datatable->setDebug(1);

  $query = $datatable->execQuery("select type_pengaturan,tb_master_pengaturan_umum.nama_pengaturan,tb_master_pengaturan_umum.isi_pengaturan,tb_master_pengaturan_umum.id_pengaturan from tb_master_pengaturan_umum where tampil='Y'",$columns);

  //buat inisialisasi array data
  $data = array();

  
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $value->nama_pengaturan;
    if ($value->type_pengaturan=='image') {
      $lebar = "";
        list($width, $height, $type, $attr) = getimagesize("$value->isi_pengaturan");
        if ($width>100) {
          $lebar = "style='width:150px'";
        }
      $ResultData[] = "<img src='$value->isi_pengaturan' $lebar>";
    } else {
      $ResultData[] = $value->isi_pengaturan;
    }
    $ResultData[] = $value->id_pengaturan;

    $data[] = $ResultData;
    
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>