<?php
session_start();
include "inc/config.php";

$notin = $db->query("select (select DATE_FORMAT(rb.paymentDate,'%y-%m-%d %H:%i:%s') from report_briva rb where virtual_account.no_pendaftaran=rb.custCode) as tanggal_bayar,virtual_account.* 
from virtual_account where is_lunas='N' and is_active='Y' and id_bank='01' and no_pendaftaran 
in(select custCode from report_briva) and no_pendaftaran not in(select no_pendaftaran from virtual_account va where va.is_lunas='Y')
and (select date(paymentDate) from report_briva rb where virtual_account.no_pendaftaran=rb.custCode) <= date(virtual_account.exp_date)
and date(virtual_account.created) <= (select date(paymentDate) from report_briva rb where virtual_account.no_pendaftaran=rb.custCode)");
echo $db->getErrorMessage();

if ($notin->rowCount()>0) {
	foreach ($notin as $va) {
		$array_va[] = array(
            'nominal' => $va->nominal,
            'no_va' => $va->no_va,
            'nama' => $va->nama,
            'exp_date' => $va->exp_date,
            'no_pendaftaran' => $va->no_pendaftaran,
            'is_lunas' => 'Y',
            'is_active' => 'N',
            'jenis_bayar' => 'formulir',
            'is_affirmasi' => 'N',
            'created' => $va->created,
            'updated' => $va->tanggal_bayar,
            'payment_date' => $va->tanggal_bayar,
            'billing_id' => $va->billing_id,
            'id_bank' => $va->id_bank,
        );
        $array_nomor[] = $va->no_pendaftaran;
	}
	$no_pendaftaran_imp = implode(",", $array_nomor);
	dump($array_va);
	$va = $db->query("delete from  virtual_account where no_pendaftaran in($no_pendaftaran_imp)");
	foreach ($array_nomor as $no_pendaftaran) {
		// Check if already assigned
    $cek_jadwal = $db->fetchCustomSingle("SELECT * FROM jadwal_peserta WHERE id_peserta=?", array('id_peserta' => $no_pendaftaran));
   
    if (!$cek_jadwal) {
        // 1. Get all kelas_ujian ordered by tanggal and sesi_ujian
        $sql = "SELECT ku.*, ju.tanggal, ju.sesi_ujian FROM kelas_ujian ku JOIN jadwal_ujian ju ON ku.id_jadwal = ju.id WHERE ju.tipe = 'offline' ORDER BY ju.tanggal ASC, ju.sesi_ujian ASC";
        $kelas_stmt = $db->query($sql);
        $assigned = false;
        foreach ($kelas_stmt as $kelas) {

            // 2. Count peserta in this kelas_ujian
            $count_obj = $db->fetchCustomSingle("SELECT COUNT(*) as jml FROM jadwal_peserta WHERE id_kelas_ujian = ?", [$kelas->id]);
            $count = $count_obj ? $count_obj->jml : 0;

            // 3. If not full, assign here
            if ($count < $kelas->kapasitas) {
                $kursi = $db->fetchCustomSingle("SELECT nomor_kursi FROM jadwal_peserta WHERE id_kelas_ujian = ? order by nomor_kursi desc limit 1", [$kelas->id]);
                $nomor_kursi = $kursi->nomor_kursi + 1;
                //generate nomor ujian
                $periode_aktif = $db->fetchSingleRow("tb_periode_tahun","status_aktif", "Y");
                $nomor = $no_pendaftaran;
                $user_gelombang = $db->fetchCustomSingle("select kode_jenis from tb_user_daftar_step inner join jenis_seleksi using(id_jenis_seleksi) where nomor_pendaftaran=? ", array($nomor));
                $tahun_short = substr($periode_aktif->tahun, 2, 2);
                $gelombang_kode = (strlen($user_gelombang->kode_jenis) === 1) ? $user_gelombang->kode_jenis . '0' : $user_gelombang->kode_jenis;
                $count_nomor_ujian = $db->fetchCustomSingle("select nomor_ujian from tb_user_daftar_step inner join jenis_seleksi using(id_jenis_seleksi) where nomor_ujian!='' and jenis_seleksi.periode_daftar=? and nomor_pendaftaran in(select no_pendaftaran from virtual_account where is_lunas='Y') order by nomor_ujian desc limit 1", array($periode_aktif->tahun));
                if ($count_nomor_ujian) {
                    $nomor_ujian = $count_nomor_ujian->nomor_ujian;
                } else {
                    $nomor_ujian = $gelombang_kode . $tahun_short . '0000';
                }

                // Extract the prefix (all but the last 4 digits)
                $prefix = substr($nomor_ujian, 0, -4);

                // Extract the last 4 digits and convert to integer
                $last_four = intval(substr($nomor_ujian, -4));

                // Increment the number
                $incremented = $last_four + 1;

                // Pad the incremented number to 4 digits
                $padded_number = str_pad($incremented, 4, '0', STR_PAD_LEFT);

                // Combine prefix and padded number
                $new_nomor_ujian = $prefix . $padded_number;

                // Insert assignment
                $db->insert('jadwal_peserta', [
                    'id_peserta' => $no_pendaftaran,
                    'nomor_ujian' => $new_nomor_ujian,
                    'id_kelas_ujian' => $kelas->id,
                    'nomor_kursi' => $nomor_kursi,
                    'status' => 'terjadwal',
                    'waktu_assign' => date('Y-m-d H:i:s')
                ]);
                $db->update('tb_user_daftar_step', [
                    'nomor_ujian' => $new_nomor_ujian,
                    'date_updated' => date('Y-m-d H:i:s'),
                ], 'nomor_pendaftaran', $no_pendaftaran);
                $assigned = true;
                break;
            }
        }
        if (!$assigned) {
            // Handle if all kelas are full
            http_response_code(409);
            echo json_encode(['status' => 'error', 'message' => 'Semua kelas ujian penuh']);
            exit;
        }
    }
	}
	$db->insertMulti("virtual_account",$array_va);
}