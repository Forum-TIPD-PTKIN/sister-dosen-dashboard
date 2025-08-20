<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_ref_sdm.nama_sdm',
    'tb_ref_sdm.nidn',
    'tb_ref_sdm.nip',
    'tb_ref_sdm.nuptk',
    'tb_ref_sdm.nama_status_aktif',
    'tb_ref_sdm.nama_status_pegawai',
    'tb_ref_sdm.jenis_sdm',
    'tb_ref_sdm.created_at',
    'tb_ref_sdm.id_sdm',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_ref_sdm.nidn","tb_ref_sdm.id_sdm");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tb_ref_sdm.id_sdm desc");


  //set group by column
  //$datatable->setGroupBy("tb_ref_sdm.id_sdm");

  $query = $datatable->execQuery("select tb_ref_sdm.nama_sdm,tb_ref_sdm.nidn,tb_ref_sdm.nip,tb_ref_sdm.nuptk,tb_ref_sdm.nama_status_aktif,tb_ref_sdm.nama_status_pegawai,tb_ref_sdm.jenis_sdm,tb_ref_sdm.created_at,tb_ref_sdm.id_sdm from tb_ref_sdm",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = $datatable->number($i);
    $ResultData[] = $value->nama_sdm;
    $ResultData[] = $value->nidn;
    $ResultData[] = $value->nip;
    $ResultData[] = $value->nuptk;
    $ResultData[] = $value->nama_status_aktif;
    $ResultData[] = $value->nama_status_pegawai;
    $ResultData[] = $value->jenis_sdm;
    $ResultData[] = tgl_time($value->created_at);
    $ResultData[] = $value->id_sdm;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>