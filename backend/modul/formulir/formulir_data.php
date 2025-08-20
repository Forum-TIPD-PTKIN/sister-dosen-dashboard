<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mahasiswa.mhs_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("mahasiswa.tgl_lahir","mahasiswa.mhs_id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("mahasiswa.mhs_id desc");


  //set group by column
  //$datatable->setGroupBy("mahasiswa.mhs_id");

  $query = $datatable->execQuery("select mahasiswa.nim,mahasiswa.nama,mahasiswa.mhs_id from mahasiswa",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->mhs_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>