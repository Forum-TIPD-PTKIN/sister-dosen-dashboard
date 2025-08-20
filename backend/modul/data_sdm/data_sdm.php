<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "data_sdm_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_ref_sdm","id_sdm",uri_segment(2));
          if ($db->userCan("update")) {
             include "data_sdm_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_ref_sdm","id_sdm",uri_segment(2));
    include "data_sdm_detail.php";
    break;
    default:
    include "data_sdm_view.php";
    break;
}

?>