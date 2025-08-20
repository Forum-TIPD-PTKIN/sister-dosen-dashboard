<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "formulir_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("mahasiswa","mhs_id",uri_segment(2));
          if ($db->userCan("update")) {
             include "formulir_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("mahasiswa","mhs_id",uri_segment(2));
    include "formulir_detail.php";
    break;
    default:
    include "formulir_view.php";
    break;
}

?>