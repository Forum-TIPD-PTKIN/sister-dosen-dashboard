<?php
session_start();
include "../../inc/config.php";
 $data_edit = $db->fetchSingleRow("sys_users","id",$_POST['id']);
?>
<img src="<?=$data_edit->foto_user;?>">