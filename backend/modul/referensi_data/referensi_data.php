<?php
switch (uri_segment(1)) {
    case "detail":
    $data_edit = $db->fetchSingleRow("reference_data","id",uri_segment(2));
    include "referensi_data_detail.php";
    break;
    default:
    include "referensi_data_view.php";
    break;
}

?>