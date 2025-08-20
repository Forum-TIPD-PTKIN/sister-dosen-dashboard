<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  case "in":
    
  
  
  
  $data = array(
      "nama" => $_POST["nama"],
      "jk" => $_POST["jk"],
      "no_hp" => $_POST["no_hp"],
      "nik" => $_POST["nik"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $_POST["tgl_lahir"],
  );
  
  
  
   
    $in = $db->insert("mahasiswa",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("mahasiswa","mhs_id",$_POST["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("mahasiswa","mhs_id",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "nama" => $_POST["nama"],
      "jk" => $_POST["jk"],
      "no_hp" => $_POST["no_hp"],
      "nik" => $_POST["nik"],
      "tmpt_lahir" => $_POST["tmpt_lahir"],
      "tgl_lahir" => $_POST["tgl_lahir"],
   );
   
   
   

    
    
    $up = $db->update("mahasiswa",$data,"mhs_id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>