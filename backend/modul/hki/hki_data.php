<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_ref_sdm.nama_sdm',
    'tb_data_hki.judul',
    'tb_data_hki.kategori_kegiatan',
    'tb_data_hki.jenis_publikasi',
    'tb_data_hki.tanggal',
    'tb_data_hki.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_data_hki.judul","tb_data_hki.id");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tb_data_hki.id desc");


  //set group by column
  //$datatable->setGroupBy("tb_data_hki.id");

  $query = $datatable->execQuery("select tb_ref_sdm.nama_sdm,tb_data_hki.judul,tb_data_hki.kategori_kegiatan,tb_data_hki.jenis_publikasi,tb_data_hki.tanggal,tb_data_hki.id from tb_data_hki inner join tb_ref_sdm on tb_data_hki.id_sdm=tb_ref_sdm.id_sdm",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable->number($i);
  
    $ResultData[] = $value->nama_sdm;
    $ResultData[] = $value->judul;
    $ResultData[] = $value->kategori_kegiatan;
    $ResultData[] = $value->jenis_publikasi;
    $ResultData[] = $value->tanggal;
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>