<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_user_daftar_step.step_user',
    'tb_user_daftar_step.email_user',
    'jenis_seleksi.nama_jenis_seleksi',
    'tb_user_daftar_step.nomor_pendaftaran',
    'tb_user_daftar_step.npsn',
    'tb_user_daftar_step.nama_sekolah',
    'tb_user_daftar_step.id_step',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  //$datatable->setDisableSearchColumn("tb_user_daftar_step.nomor_pendaftaran","tb_user_daftar_step.id_step");
  
  //set numbering is true
  $datatable->setNumberingStatus(1);

  //set order by column
  $datatable->setOrderBy("tb_user_daftar_step.id_step desc");


  //set group by column
  //$datatable->setGroupBy("tb_user_daftar_step.id_step");

  $query = $datatable->execQuery("select tb_user_daftar_step.step_user,tb_user_daftar_step.email_user,jenis_seleksi.nama_jenis_seleksi,tb_user_daftar_step.nomor_pendaftaran,tb_user_daftar_step.npsn,tb_user_daftar_step.nama_sekolah,tb_user_daftar_step.id_step from tb_user_daftar_step inner join jenis_seleksi on tb_user_daftar_step.id_jenis_seleksi=jenis_seleksi.id_jenis_seleksi",$columns);

  //buat inisialisasi array data
  $data = array();
  $user = array('root','administrator');
  $i=1;
  foreach ($query as $value) {
     $login_as = '';
    if (in_array($_SESSION['group_level'], $user)) {
      $login_as = '<a target="_blank" href="'.base_admin().'inc/loginas_user.php?email='.$value->email_user.'" class="btn btn-success btn-xs" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }
    //array data
    $ResultData = array();
  $ResultData[] = $datatable->number($i);
  
    $ResultData[] = $value->step_user;
    $ResultData[] = $value->email_user.' '. $login_as;
    $ResultData[] = $value->nama_jenis_seleksi;
    $ResultData[] = $value->nomor_pendaftaran;
    $ResultData[] = $value->npsn;
    $ResultData[] = $value->nama_sekolah;
    $ResultData[] = $value->id_step;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable->setData($data);
//create our json
$datatable->createData();

?>