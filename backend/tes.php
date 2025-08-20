<?php
include "inc/config.php";

$date = new DateTime("-6 Year");;
exit();
echo $date->format('Y'); // Prints 'December 2014'
$tahun = $date->format('Y');

for ($i = $tahun; $i <= date('Y'); $i++) { 
	echo $i;
}
exit();
$data = $db->query("select  mulai_smt from tb_master_mahasiswa
inner join tb_data_pendaftaran using(nim)");
foreach ($data as $dt) {
	$angkatan[$dt->mulai_smt] = $mulai_smt;
}

print_r($angkatan);