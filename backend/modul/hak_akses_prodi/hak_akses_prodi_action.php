<?php
session_start();
include "../../inc/config.php";
session_check_json();

$data_insert = array('akses' => $_POST['data_ids']);
$json_encode_data = json_encode($data_insert);
$data = array('akses_prodi' => $json_encode_data);
$db->update('sys_group_users',$data,'id',$_POST['id_group']);
?>