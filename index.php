<?php
session_start();
include "backend/inc/config.php";
include "includes/config.php";

$uri0 = uri_segment(0);
$uri1 = uri_segment(1);

// Kalau akses ke /backend, lempar ke backend/index.php
if ($uri0 === 'backend') {
    include __DIR__ . "/backend/index.php";
    exit;
}

switch ($uri0) {
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
    default:
        if ($uri0 != "") {
            include "not_found.php";
        }
        break;
}
