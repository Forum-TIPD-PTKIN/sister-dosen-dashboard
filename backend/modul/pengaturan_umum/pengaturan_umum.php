<?php
switch (uri_segment(1)) {
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_master_pengaturan_umum","id_pengaturan",uri_segment(2));
    include "pengaturan_umum_detail.php";
    break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_master_pengaturan_umum","id_pengaturan",uri_segment(2));
      if ($db->userCan("update")) {
         include "edit_extend.php";
      } 
      break;
    default:
    include "pengaturan_umum_view.php";
    break;
}

?>