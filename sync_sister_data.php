<?php
// Ambil data SISTER dan isi tabel utama dashboard.

set_time_limit(0);

require_once __DIR__ . '/backend/inc/config.php';
require_once __DIR__ . '/includes/config.php';

if (!isset($api) || !$api instanceof SisterAPI) {
    $api = new SisterAPI();
    $api->authenticate();
}

function rowsFromResponse($response)
{
    if (!is_array($response)) {
        return [];
    }

    if (isset($response['data']) && is_array($response['data'])) {
        return $response['data'];
    }

    if (array_keys($response) === range(0, count($response) - 1)) {
        return $response;
    }

    return [];
}

function valueFrom(array $row, array $keys, $default = null)
{
    foreach ($keys as $key) {
        if (array_key_exists($key, $row) && $row[$key] !== '') {
            return $row[$key];
        }
    }

    return $default;
}

function trimOrNull($value)
{
    if ($value === null) {
        return null;
    }

    $value = trim((string) $value);
    return $value === '' ? null : $value;
}

function textValue($value, $maxLength, $default = '-')
{
    $value = trimOrNull($value);
    if ($value === null) {
        $value = $default;
    }

    if ($value === null) {
        return null;
    }

    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength);
    }

    return substr($value, 0, $maxLength);
}

function jsonOrNull($value)
{
    return $value === null ? null : json_encode($value, JSON_UNESCAPED_UNICODE);
}

function dateOrNull($value)
{
    if (!$value) {
        return null;
    }

    $date = substr((string) $value, 0, 10);
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : null;
}

function yearOrNull($value)
{
    if (!$value) {
        return null;
    }

    return preg_match('/^\d{4}$/', (string) $value) ? (string) $value : null;
}

function runQuery($db, $sql, ?array $params = null)
{
    $result = $db->query($sql, $params);
    if ($result === false) {
        throw new RuntimeException($db->getErrorMessage());
    }

    return $result;
}

function countTableRows($db, $table)
{
    $result = runQuery($db, 'SELECT COUNT(*) AS total FROM ' . $table);
    $row = $result->fetch();

    return (int) $row->total;
}

function syncSDM($db, SisterAPI $api)
{
    $sdmList = rowsFromResponse($api->getReferensi('sdm'));
    if (empty($sdmList)) {
        throw new RuntimeException('Data SDM dari SISTER kosong. Sinkronisasi dibatalkan agar data lama tidak terhapus.');
    }

    runQuery($db, 'DELETE FROM tb_ref_sdm');

    $count = 0;
    foreach ($sdmList as $sdm) {
        $idSdm = valueFrom($sdm, ['id_sdm', 'id']);
        if (!$idSdm) {
            continue;
        }

        runQuery(
            $db,
            'REPLACE INTO tb_ref_sdm
                (id_sdm, nama_sdm, nidn, nip, nuptk, nama_status_aktif, nama_status_pegawai, jenis_sdm, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
            [
                $idSdm,
                trimOrNull(valueFrom($sdm, ['nama_sdm', 'nama'])),
                trimOrNull(valueFrom($sdm, ['nidn'])),
                trimOrNull(valueFrom($sdm, ['nip'])),
                trimOrNull(valueFrom($sdm, ['nuptk'])),
                trimOrNull(valueFrom($sdm, ['nama_status_aktif', 'status_aktif'])),
                trimOrNull(valueFrom($sdm, ['nama_status_pegawai', 'status_pegawai'])),
                trimOrNull(valueFrom($sdm, ['jenis_sdm'])),
            ]
        );
        $count++;
    }

    return [$sdmList, $count];
}

function syncSDMMetadata($db, SisterAPI $api, array $sdmList)
{
    runQuery($db, 'DELETE FROM tb_ref_sdm_pendidikan');
    runQuery($db, 'DELETE FROM tb_ref_sdm_penugasan');
    runQuery($db, 'DELETE FROM tb_ref_sdm_jabatan_fungsional');

    $counts = [
        'pendidikan' => 0,
        'penugasan' => 0,
        'jabatan_fungsional' => 0,
    ];

    foreach ($sdmList as $sdm) {
        $idSdm = valueFrom($sdm, ['id_sdm', 'id']);
        if (!$idSdm) {
            continue;
        }

        foreach (rowsFromResponse($api->getPendidikanFormal($idSdm)) as $pendidikan) {
            $jenjang = valueFrom($pendidikan, ['jenjang_pendidikan']);
            if (!in_array($jenjang, ['S1', 'S2', 'S3'], true)) {
                continue;
            }

            $dataPendidikan = [
                'id' => valueFrom($pendidikan, ['id']),
                'jenjang_pendidikan' => $jenjang,
                'gelar_akademik' => valueFrom($pendidikan, ['gelar_akademik']),
                'bidang_studi' => valueFrom($pendidikan, ['bidang_studi']),
                'nama_perguruan_tinggi' => valueFrom($pendidikan, ['nama_perguruan_tinggi']),
                'tahun_lulus' => valueFrom($pendidikan, ['tahun_lulus']),
                'jenis_ajuan' => valueFrom($pendidikan, ['jenis_ajuan']),
            ];

            runQuery(
                $db,
                'INSERT INTO tb_ref_sdm_pendidikan (id_sdm, jenjang_pendidikan, datas) VALUES (?, ?, ?)',
                [$idSdm, $jenjang, json_encode($dataPendidikan, JSON_UNESCAPED_UNICODE)]
            );
            $counts['pendidikan']++;
        }

        foreach (rowsFromResponse($api->getPenugasan($idSdm)) as $penugasan) {
            $isHomebase = valueFrom($penugasan, ['apakah_penugasan_homebase']);
            if ($isHomebase !== null && strtolower((string) $isHomebase) !== 'ya') {
                continue;
            }

            $unitKerja = trimOrNull(valueFrom($penugasan, ['unit_kerja']));
            if (!$unitKerja) {
                continue;
            }

            $jenjang = trimOrNull(valueFrom($penugasan, ['jenjang_pendidikan']));
            $homebase = $jenjang ? "Program Studi $jenjang $unitKerja" : $unitKerja;

            runQuery(
                $db,
                'INSERT INTO tb_ref_sdm_penugasan (id_sdm, unit_kerja) VALUES (?, ?)',
                [$idSdm, textValue($homebase, 200)]
            );
            $counts['penugasan']++;
        }

        foreach (rowsFromResponse($api->getJabatanFungsional($idSdm)) as $jabatan) {
            $namaJabatan = trimOrNull(valueFrom($jabatan, ['jabatan_fungsional']));
            if (!$namaJabatan) {
                continue;
            }

            runQuery(
                $db,
                'INSERT INTO tb_ref_sdm_jabatan_fungsional (id_sdm, jabatan_fungsional, sk, tanggal_mulai) VALUES (?, ?, ?, ?)',
                [
                    $idSdm,
                    textValue($namaJabatan, 200),
                    textValue(valueFrom($jabatan, ['sk']), 200, ''),
                    textValue(valueFrom($jabatan, ['tanggal_mulai']), 200, ''),
                ]
            );
            $counts['jabatan_fungsional']++;
        }
    }

    return $counts;
}

function syncPenelitian($db, SisterAPI $api, array $sdmList)
{
    runQuery($db, 'DELETE FROM tb_data_penelitian');

    $count = 0;
    foreach ($sdmList as $sdm) {
        $idSdm = valueFrom($sdm, ['id_sdm', 'id']);
        if (!$idSdm) {
            continue;
        }

        foreach (rowsFromResponse($api->getPenelitian($idSdm)) as $row) {
            $idPenelitian = valueFrom($row, ['id_penelitian', 'id']);
            if (!$idPenelitian) {
                continue;
            }

            $tahunPelaksanaan = yearOrNull(valueFrom($row, ['tahun_pelaksanaan', 'tahun']));
            if (!$tahunPelaksanaan) {
                continue;
            }

            runQuery(
                $db,
                'INSERT INTO tb_data_penelitian
                    (id_penelitian, id_sdm, judul, bidang_keilmuan, tahun_pelaksanaan, lama_kegiatan)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $idPenelitian,
                    $idSdm,
                    textValue(valueFrom($row, ['judul']), 500),
                    jsonOrNull(valueFrom($row, ['bidang_keilmuan'])),
                    $tahunPelaksanaan,
                    (int) valueFrom($row, ['lama_kegiatan'], 0),
                ]
            );
            $count++;
        }
    }

    return $count;
}

function syncPublikasi($db, SisterAPI $api, array $sdmList)
{
    runQuery($db, 'DELETE FROM tb_data_publikasi');

    $count = 0;
    foreach ($sdmList as $sdm) {
        $idSdm = valueFrom($sdm, ['id_sdm', 'id']);
        if (!$idSdm) {
            continue;
        }

        foreach (rowsFromResponse($api->getPublikasi($idSdm)) as $row) {
            $tanggal = dateOrNull(valueFrom($row, ['tanggal']));
            if (!$tanggal) {
                continue;
            }

            runQuery(
                $db,
                'INSERT INTO tb_data_publikasi
                    (id_kategori_kegiatan, id_sdm, a_klaim_bkd, wkt_klaim_bkd, judul, quartile, jenis_publikasi, tanggal, kategori_kegiatan, asal_data, bidang_keilmuan)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    (int) valueFrom($row, ['id_kategori_kegiatan'], 0),
                    $idSdm,
                    (int) valueFrom($row, ['a_klaim_bkd'], 0),
                    valueFrom($row, ['wkt_klaim_bkd']),
                    textValue(valueFrom($row, ['judul']), 500),
                    textValue(valueFrom($row, ['quartile']), 50, null),
                    textValue(valueFrom($row, ['jenis_publikasi']), 200),
                    $tanggal,
                    textValue(valueFrom($row, ['kategori_kegiatan']), 500),
                    textValue(valueFrom($row, ['asal_data']), 100),
                    jsonOrNull(valueFrom($row, ['bidang_keilmuan'])),
                ]
            );
            $count++;
        }
    }

    return $count;
}

function syncHKI($db, SisterAPI $api, array $sdmList)
{
    runQuery($db, 'DELETE FROM tb_data_hki');

    $count = 0;
    foreach ($sdmList as $sdm) {
        $idSdm = valueFrom($sdm, ['id_sdm', 'id']);
        if (!$idSdm) {
            continue;
        }

        foreach (rowsFromResponse($api->getKekayaanIntelektual($idSdm)) as $row) {
            $idHki = valueFrom($row, ['id_hki', 'id']);
            if (!$idHki) {
                continue;
            }

            $tanggal = dateOrNull(valueFrom($row, ['tanggal']));
            if (!$tanggal) {
                continue;
            }

            runQuery(
                $db,
                'REPLACE INTO tb_data_hki
                    (id, id_sdm, kategori_kegiatan, judul, quartile, bidang_keilmuan, jenis_publikasi, tanggal)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $idHki,
                    $idSdm,
                    textValue(valueFrom($row, ['kategori_kegiatan']), 255),
                    textValue(valueFrom($row, ['judul']), 255),
                    textValue(valueFrom($row, ['quartile']), 50, null),
                    jsonOrNull(valueFrom($row, ['bidang_keilmuan'])),
                    textValue(valueFrom($row, ['jenis_publikasi']), 100),
                    $tanggal,
                ]
            );
            $count++;
        }
    }

    return $count;
}

function syncPengabdian($db, SisterAPI $api, array $sdmList)
{
    runQuery($db, 'DELETE FROM tb_data_pengabdian');

    $count = 0;
    foreach ($sdmList as $sdm) {
        $idSdm = valueFrom($sdm, ['id_sdm', 'id']);
        if (!$idSdm) {
            continue;
        }

        foreach (rowsFromResponse($api->getPengabdian($idSdm)) as $row) {
            $idPengabdian = valueFrom($row, ['id_pengabdian', 'id']);
            if (!$idPengabdian) {
                continue;
            }

            $tahunPelaksanaan = yearOrNull(valueFrom($row, ['tahun_pelaksanaan', 'tahun']));
            if (!$tahunPelaksanaan) {
                continue;
            }

            runQuery(
                $db,
                'REPLACE INTO tb_data_pengabdian
                    (id, judul, id_sdm, bidang_keilmuan, tahun_pelaksanaan, lama_kegiatan)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $idPengabdian,
                    textValue(valueFrom($row, ['judul']), 255),
                    $idSdm,
                    jsonOrNull(valueFrom($row, ['bidang_keilmuan'])),
                    $tahunPelaksanaan,
                    (int) valueFrom($row, ['lama_kegiatan'], 0),
                ]
            );
            $count++;
        }
    }

    return $count;
}

function runSisterDashboardSync($db, SisterAPI $api)
{
    [$sdmList, $totalSdm] = syncSDM($db, $api);
    $metadataCounts = syncSDMMetadata($db, $api, $sdmList);
    syncPenelitian($db, $api, $sdmList);
    syncPublikasi($db, $api, $sdmList);
    syncHKI($db, $api, $sdmList);
    syncPengabdian($db, $api, $sdmList);

    return [
        'sdm' => $totalSdm,
        'penelitian' => countTableRows($db, 'tb_data_penelitian'),
        'publikasi' => countTableRows($db, 'tb_data_publikasi'),
        'hki' => countTableRows($db, 'tb_data_hki'),
        'pengabdian' => countTableRows($db, 'tb_data_pengabdian'),
        'pendidikan' => $metadataCounts['pendidikan'],
        'penugasan' => $metadataCounts['penugasan'],
        'jabatan_fungsional' => $metadataCounts['jabatan_fungsional'],
    ];
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    try {
        $stats = runSisterDashboardSync($db, $api);

        echo "Sync completed.\n";
        echo "SDM: {$stats['sdm']}\n";
        echo "Penelitian: {$stats['penelitian']}\n";
        echo "Publikasi: {$stats['publikasi']}\n";
        echo "HKI: {$stats['hki']}\n";
        echo "Pengabdian: {$stats['pengabdian']}\n";
    } catch (Throwable $exception) {
        http_response_code(500);
        echo "Sync failed: " . $exception->getMessage() . "\n";
        exit(1);
    }
}
