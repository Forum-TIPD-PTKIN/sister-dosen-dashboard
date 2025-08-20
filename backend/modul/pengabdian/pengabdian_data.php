<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_ref_sdm.nama_sdm',
    'tb_data_pengabdian.tahun_pelaksanaan',
    'tb_data_pengabdian.lama_kegiatan',
    'tb_data_pengabdian.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_data_pengabdian.judul","tb_data_pengabdian.id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tb_data_pengabdian.id desc");


  //set group by column
  //$datatable->setGroupBy("tb_data_pengabdian.id");

  $query = $datatable->execQuery("select tb_ref_sdm.nama_sdm,tb_data_pengabdian.tahun_pelaksanaan,tb_data_pengabdian.lama_kegiatan,tb_data_pengabdian.id from tb_data_pengabdian inner join tb_ref_sdm on tb_data_pengabdian.id_sdm=tb_ref_sdm.id_sdm",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable->number($i);
  
    $ResultData[] = $value->nama_sdm;
    $ResultData[] = $value->tahun_pelaksanaan;
    $ResultData[] = $value->lama_kegiatan;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>