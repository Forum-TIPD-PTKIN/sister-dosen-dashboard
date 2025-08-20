<?php
session_check_adm();
switch (uri_segment(1)) {
	case 'create':
		include "excel/add.php";
		break;
	case 'createdownloader':
		include "downloader/add.php";
		break;
	default:
		include "view.php";
		break;
}

?>
