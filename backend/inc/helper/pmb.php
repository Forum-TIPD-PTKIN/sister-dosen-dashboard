<?php
function getProdiJenjang() { 
  global $db;
  //echo "select * from view_prodi_jenjang ".get_akses_prodi();
  $prodi_jenjang = $db->query("select * from view_prodi_jenjang");
  foreach ($prodi_jenjang as $prodi) {
    $data_prodi[$prodi->kode_jur] = string_rapih($prodi->nama_jurusan);
  }
  return $data_prodi;
}