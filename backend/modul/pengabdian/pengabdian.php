<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "pengabdian_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_data_pengabdian","id",uri_segment(2));
          if ($db->userCan("update")) {
             include "pengabdian_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_data_pengabdian","id",uri_segment(2));
    include "pengabdian_detail.php";
    break;
    default:
    include "pengabdian_view.php";
    break;
}

?>