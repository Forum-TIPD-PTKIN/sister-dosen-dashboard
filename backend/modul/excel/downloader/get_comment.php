<?php
include "../../../inc/config.php";
$column = $db->query("show FULL columns from ".$_POST['table_name']);
foreach ($column as $col) {
	if ($col->Field==$_POST['col_name']) {
		if ($col->Comment=="") {
			echo $col->Field;
		} else {
			echo $col->Comment;
		}
	}
  
}