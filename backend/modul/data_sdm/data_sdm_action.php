<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nama_sdm" => $_POST["nama_sdm"],
      "nidn" => $_POST["nidn"],
  );
  
  
  
   
    $in = $db->insert("tb_ref_sdm",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_ref_sdm","id_sdm",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_ref_sdm","id_sdm",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nama_sdm" => $_POST["nama_sdm"],
      "nidn" => $_POST["nidn"],
   );
   
   
   

    
    
    $up = $db->update("tb_ref_sdm",$data,"id_sdm",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>