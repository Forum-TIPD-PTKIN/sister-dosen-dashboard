<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "all_registrar_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_user_daftar_step","id_step",uri_segment(2));
          if ($db->userCan("update")) {
             include "all_registrar_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_user_daftar_step","id_step",uri_segment(2));
    include "all_registrar_detail.php";
    break;
    default:
    include "all_registrar_view.php";
    break;
}

?>