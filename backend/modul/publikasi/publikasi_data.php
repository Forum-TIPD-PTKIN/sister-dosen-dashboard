<?php
session_start();
include "../../inc/config.php";

$columns = array(
    '(select view_nama_gelar.nama_dengan_gelar from view_nama_gelar where view_nama_gelar.id_sdm=tb_data_publikasi.id_sdm)',
    'tb_data_publikasi.judul',
    'tb_data_publikasi.jenis_publikasi',
    'tb_data_publikasi.tanggal',
    'tb_data_publikasi.asal_data',
    'tb_data_publikasi.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_data_publikasi.judul","tb_data_publikasi.id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tb_data_publikasi.id desc");


  //set group by column
  //$datatable->setGroupBy("tb_data_publikasi.id");
$datatable->setDebug(1);
  $datatable->setFromQuery("tb_data_publikasi");
  $query = $datatable->execQuery("select 
  (select view_nama_gelar.nama_dengan_gelar from view_nama_gelar where view_nama_gelar.id_sdm=tb_data_publikasi.id_sdm) as nama_dengan_gelar,tb_data_publikasi.judul,tb_data_publikasi.jenis_publikasi,tb_data_publikasi.tanggal,tb_data_publikasi.asal_data,tb_data_publikasi.id from tb_data_publikasi",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable->number($i);
  
    $ResultData[] = $value->nama_dengan_gelar;
    $ResultData[] = $value->judul;
    $ResultData[] = $value->jenis_publikasi;
    $ResultData[] = $value->tanggal;
    $ResultData[] = $value->asal_data;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>