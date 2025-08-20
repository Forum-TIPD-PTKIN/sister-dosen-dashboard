<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "penelitian_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_data_penelitian","id",uri_segment(2));
          if ($db->userCan("update")) {
             include "penelitian_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_data_penelitian","id",uri_segment(2));
    include "penelitian_detail.php";
    break;
    default:
    include "penelitian_view.php";
    break;
}

?>