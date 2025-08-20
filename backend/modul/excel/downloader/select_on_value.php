<?php
include "../../../inc/config.php";
$tb=$_GET['tb'];

$on = $db2->query("show columns from $tb");

foreach ($on as $col) {
    echo "<option value='$tb.$col->Field'>$tb.$col->Field</option>";
}
?>