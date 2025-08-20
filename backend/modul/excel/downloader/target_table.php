<?php
include "../../../inc/config.php";
echo '<option value="">Select Table</option>';
foreach ($db->query('show table status') as $tb) {
	echo "<option value='$tb->Name'>$tb->Name</option>";
}