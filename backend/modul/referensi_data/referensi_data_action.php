<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "reference_type" => $_POST["reference_type"],
      "reference_id" => $_POST["reference_id"],
      "data" => $_POST["data"],
      "nama" => $_POST["nama"],
  );
  
  
  
   
    $in = $db->insert("reference_data",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("reference_data","id",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("reference_data","id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "reference_type" => $_POST["reference_type"],
      "reference_id" => $_POST["reference_id"],
      "data" => $_POST["data"],
      "nama" => $_POST["nama"],
   );
   
   
   

    
    
    $up = $db->update("reference_data",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>