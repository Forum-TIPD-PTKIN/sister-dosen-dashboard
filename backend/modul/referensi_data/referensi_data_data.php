<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_reference_data.reference_type',
    'tb_reference_data.reference_id',
    'tb_reference_data.nama',
    'tb_reference_data.created_at',
    'tb_reference_data.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_reference_data.nama","tb_reference_data.id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  //$datatable->setOrderBy("tb_reference_data.id desc");


  //set group by column
  //$datatable->setGroupBy("tb_reference_data.id");

  $query = $datatable->execQuery("select tb_reference_data.reference_type,tb_reference_data.reference_id,tb_reference_data.nama,tb_reference_data.created_at,tb_reference_data.id from tb_reference_data",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->reference_type;
    $ResultData[] = $value->reference_id;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->created_at;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>