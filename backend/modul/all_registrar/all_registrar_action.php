<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nomor_pendaftaran" => $_POST["nomor_pendaftaran"],
  );
  
  
  
   
    $in = $db->insert("tb_user_daftar_step",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("tb_user_daftar_step","id_step",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("tb_user_daftar_step","id_step",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nomor_pendaftaran" => $_POST["nomor_pendaftaran"],
   );
   
   
   

    
    
    $up = $db->update("tb_user_daftar_step",$data,"id_step",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>