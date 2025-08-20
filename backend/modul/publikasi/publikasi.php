<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "publikasi_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_data_publikasi","id",uri_segment(2));
          if ($db->userCan("update")) {
             include "publikasi_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_data_publikasi","id",uri_segment(2));
    include "publikasi_detail.php";
    break;
    default:
    include "publikasi_view.php";
    break;
}

?>