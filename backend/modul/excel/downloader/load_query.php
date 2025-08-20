<?php
include "../../../inc/config.php";


$selected_column = array();
$main_table = $_POST['table'];
$alias = array_filter($_POST['alias1']);
//dump($alias);

$query = "";
$selected_col = "";
$join = "";
$join_query = array();
if (isset($_POST['join_cond'])) {
	foreach ($_POST['join_cond'] as $key => $join) {
		$join_query[] = "\n ".$join." JOIN ".$_POST['join_with'][$key]." ON ".$_POST['join_on_first'][$key]."=".$_POST['join_on_next'][$key];
	}
}
if (!empty($join_query)) {
	$join = implode("", $join_query);
}
/*if ($_POST['dipilih1']) {
	foreach ($_POST['dipilih1'] as $key => $dt) {
		$selected_column[] = $key;
	}
}*/
foreach ($alias as $col => $val) {
	$col_name = substr(strrchr($col, '.'), 1);
	if ($col_name!=$val) {
		$selected_column[] = $col." AS ".$val;
	} else {
		$selected_column[] = $col;
	}
}
if (!empty($selected_column)) {
	$selected_col = implode(",", $selected_column);
}

$query = "SELECT $selected_col FROM $main_table $join";
$count_query = "SELECT count(*) as jumlah FROM $main_table $join";
action_response('',array('query' => $query,'count_query' => $count_query));