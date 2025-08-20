<?php
session_start();
include "backend/inc/config.php";
include "includes/config.php";

// Get the first URI segment
$uri0 = uri_segment(0);
$uri1 = uri_segment(1);
switch (uri_segment(0)) {
	case '':
		include "dashboard.php";
		break;
	case 'detail':
        $id_sdm = $uri1;
        $dosen = $db->fetchSingleRow('view_sdm', 'id_sdm', $id_sdm);
        if (!$dosen) {
            include "not_found.php";
            exit;
        }
		include 'detail_dosen.php';
		break;
	default :
	if (uri_segment(0)!="") {
		include "not_found.php";
	}
	break;
}