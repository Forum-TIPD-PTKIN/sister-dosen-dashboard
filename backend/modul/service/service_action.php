<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET['act']) {
	case 'delete':
	//get service name
	$service = $db->fetchCustomSingle("select sys_services.* FROM sys_services INNER JOIN sys_token ON sys_services.id=sys_token.id_service where sys_token.id=?",array('id' => $_POST['id']));
		if (file_exists(SITE_ROOT."/api/services/".$service->nav_act."/".$service->nav_act.".php")) {
			$db->deleteDirectory(SITE_ROOT."/api/services/".$service->nav_act);
		}
		$db->delete('sys_services','id',$service->id);
		action_response($db->getErrorMessage());
		break;
	case 'up':
	 $data = array(
	      "format_data" => $_POST["format_data"],
	   );
	  if(isset($_POST["enable_token_read"])=="on")
	  {
	    $data['enable_token_read']="Y";
	  } else {
	   	$data['enable_token_read']="N";
	  }
	  //create
	  if(isset($_POST["enable_token_create"])=="on")
	  {
	    $data['enable_token_create']="Y";
	  } else {
	   	$data['enable_token_create']="N";
	  }
	  //update
	  if(isset($_POST["enable_token_update"])=="on")
	  {
	    $data['enable_token_update']="Y";
	  } else {
	   	$data['enable_token_update']="N";
	  }
	  //delete
	  if(isset($_POST["enable_token_delete"])=="on")
	  {
	    $data['enable_token_delete']="Y";
	  } else {
	   	$data['enable_token_delete']="N";
	  }
	  
    $up = $db->update("sys_token",$data,"id",$_POST["id"]);
    
    action_response($db->getErrorMessage());
		break;
	default:
		# code...
		break;
}

?>
