<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "hki_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_data_hki","id",uri_segment(2));
          if ($db->userCan("update")) {
             include "hki_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_data_hki","id",uri_segment(2));
    include "hki_detail.php";
    break;
    default:
    include "hki_view.php";
    break;
}

?>