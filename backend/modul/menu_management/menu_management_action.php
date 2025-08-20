<?php
session_start();
include "../../inc/config.php";
//session_check_adm();

dump($_POST);


switch ($_GET["act"]) {
	case 'mass':
		$has_role = 0;
		$role = 'Y';

		$checkExist = $db->fetchCustomSingle("select * from sys_menu_role where id_menu='".$_POST['id_menu']."' and group_level='".$_POST['level']."'");

		if ($_POST['status']=='false') {
			$role = 'N';
		}
		$data_role = array(
			'id_menu' => $_POST['id_menu'],
			'group_level' => $_POST['level'],
			'read_act' => $role,
			'insert_act' => $role,
			'update_act' => $role,
			'delete_act' => $role,
			'import_act' => $role
		);

			if ($checkExist) {
				$db->update('sys_menu_role',$data_role,'id',$checkExist->id);
			} else {
				$db->insert('sys_menu_role',$data_role);
			}
		action_response($db->getErrorMessage());	

		break;
	case 'single':
		dump($_POST);
		$role = 'N';
		if ($_POST['status']=='true') {
			$role = 'Y';
		}
		$checkExist = $db->fetchCustomSingle("select * from sys_menu_role where id_menu='".$_POST['id_menu']."' and group_level='".$_POST['level']."'");
			if ($checkExist) {
				$data_role[$_POST['role']] = $role;
				$db->update('sys_menu_role',$data_role,'id',$checkExist->id);
			} else {
				$data_role = array(
					'id_menu' => $_POST['id_menu'],
					'group_level' => $_POST['level'],
					'read_act' => $role,
					'insert_act' => $role,
					'update_act' => $role,
					'delete_act' => $role,
					'import_act' => $role
				);
				$db->insert('sys_menu_role',$data_role);
			}
		action_response($db->getErrorMessage());	
		break;
	default:
		// code...
		break;
}


?>