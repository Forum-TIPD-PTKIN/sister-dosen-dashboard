<?php
require_once 'backend/inc/config.php';
require_once 'includes/config.php';
include "includes/sinta.php";
$nama_gelar = $db->fetchSingleRow('view_nama_gelar', 'id_sdm', $id_sdm)->nama_dengan_gelar ?? '';


// --- Get author name from $dosen, remove after comma if present ---
$author_name = $dosen->nama_sdm ?? '';
if (($pos = strpos($author_name, ',')) !== false) {
    $author_name = substr($author_name, 0, $pos);
}
$id_sinta = 0;
$scores = findScore($author_name);
$sinta = $scores['sinta'];
$scopus_hindex = $scores['scopus'];
$scholar_hindex = $scores['google'];
if (isset($scores['id'])) {
    $id_sinta = $scores['id'];
}

$publikasi = $db->query("SELECT * FROM tb_data_publikasi WHERE id_sdm='$id_sdm' ORDER BY tanggal DESC LIMIT 5");
$publikasi_all = $db->query("SELECT * FROM tb_data_publikasi WHERE id_sdm='$id_sdm' ORDER BY tanggal DESC");
$pengabdian = $db->query("SELECT * FROM tb_data_pengabdian WHERE id_sdm='$id_sdm' ORDER BY tahun_pelaksanaan DESC");
$ki = $db->query("SELECT * FROM tb_data_hki WHERE id_sdm='$id_sdm' ORDER BY tanggal DESC");
$sks = $db->query("SELECT semester, jenjang, SUM(sks) as total FROM tb_data_penugasan WHERE id_sdm='$id_sdm' GROUP BY semester, jenjang");
$bimbingan_akademik = $db->query("SELECT tahun, jenjang, COUNT(*) as total FROM tb_data_bimbingan WHERE id_sdm='$id_sdm' AND tipe='akademik' GROUP BY tahun, jenjang");
$bimbingan_ta = $db->query("SELECT tahun, jenjang, COUNT(*) as total FROM tb_data_bimbingan WHERE id_sdm='$id_sdm' AND tipe='ta' GROUP BY tahun, jenjang");
$tahun_now = date('Y');


// Dynamic publication trend: get last 5 years, group by year and all actual jenis_publikasi
$trend = [];
$kategori_set = [];
for ($i = 0; $i < 5; $i++) {
    $tahun = $tahun_now - $i;
    $trend[$tahun] = [];
}
$res_publikasi = $db->query("SELECT YEAR(tanggal) as tahun, jenis_publikasi, COUNT(*) as total FROM tb_data_publikasi WHERE id_sdm='$id_sdm' AND YEAR(tanggal)>=" . ($tahun_now - 4) . " GROUP BY tahun, jenis_publikasi");
foreach ($res_publikasi as $row) {
    $jenis = $row->jenis_publikasi;
    $kategori_set[$jenis] = true;
    $trend[$row->tahun][$jenis] = $row->total;
}
// Ensure all categories exist for all years
$kategori_list = array_keys($kategori_set);
foreach ($trend as $tahun => $data) {
    foreach ($kategori_list as $kat) {
        if (!isset($trend[$tahun][$kat]))
            $trend[$tahun][$kat] = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dosen - <?= $nama_gelar ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script>
        // DataTables initialization
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DataTables for all tables
            $('#publikasiTable').DataTable();
            $('#pengabdianTable').DataTable();
            $('#kiTable').DataTable();
            $('#penelitianTable').DataTable();
            // Tab switching logic
            window.showTab = function (tabId) {
                var tabs = document.querySelectorAll('.tab-content');
                tabs.forEach(function (tab) {
                    tab.classList.add('hidden');
                });
                var activeTab = document.getElementById(tabId);
                if (activeTab) activeTab.classList.remove('hidden');
                // Update active tab button style
                var btns = document.querySelectorAll('#dataTabs button');
                btns.forEach(function (btn) {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                var activeBtn = document.getElementById(tabId + '-tab');
                if (activeBtn) {
                    activeBtn.classList.add('border-blue-500', 'text-blue-600');
                    activeBtn.classList.remove('border-transparent', 'text-gray-500');
                }
            };
            // Show first tab by default
            showTab('publikasi');
        });
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="<?= getPengaturan('favicon'); ?>">
    <!-- DataTables Required JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.tailwindcss.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/js/dataTables.tailwindcss.js">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Force DataTables to always use light mode, override dark classes */
        .dt-container *,
        .dt-container .dt-paging-button,
        .dt-container .dt-paging-button.current,
        .dt-container .dt-paging-button:hover,
        .dt-container .dt-search input,
        .dt-container .dt-length select,
        .dt-container table th,
        .dt-container table td {
            background-color: #fff !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        .dt-container .dt-paging-button.current {
            background-color: #24774a !important;
            color: #fff !important;
            border-color: #24774a !important;
            box-shadow: 0 2px 8px rgba(36, 119, 74, 0.15);
        }

        /* Nomor halaman aktif pada pagination DataTables */
        .dt-container .dt-paging-button.current:not([aria-label]) {
            background-color: #24774a !important;
            color: #fff !important;
            border-color: #24774a !important;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(36, 119, 74, 0.15);
        }

        .dt-container .pagination a[aria-current="page"] {
            background-color: #24774a !important;
            color: #fff !important;
            border-color: #24774a !important;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(36, 119, 74, 0.15);
        }

        .dt-container .dt-paging-button:hover {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }

        /* Remove dark mode classes from DataTables */
        .dt-container .dark\:bg-gray-800,
        .dt-container .dark\:bg-gray-700\/25,
        .dt-container .dark\:text-gray-50,
        .dt-container .dark\:text-gray-300,
        .dt-container .dark\:border-gray-700,
        .dt-container .dark\:border-gray-700\/50,
        .dt-container .dark\:hover\:border-gray-600,
        .dt-container .dark\:hover\:text-gray-200,
        .dt-container .dark\:focus\:ring-gray-600,
        .dt-container .dark\:focus\:ring-opacity-40 {
            background-color: #fff !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        /* Improved tab styles for DataTables section */
        #dataTabs {
            background: #e6f4ec;
            border-radius: 1rem 1rem 0 0;
            box-shadow: 0 2px 8px rgba(36, 119, 74, 0.08);
            padding: 0.5rem 0.5rem 0 0.5rem;
        }

        #dataTabs button {
            transition: all 0.2s;
            font-weight: 600;
            color: #000000;
            background: transparent;
            border: none;
            outline: none;
            border-radius: 0.75rem 0.75rem 0 0;
            margin-right: 0.25rem;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 1px 2px rgba(36, 119, 74, 0.03);
            cursor: pointer;
            position: relative;
        }

        #dataTabs button:hover {
            background: #c6e6d6;
            color: #24774a;
            box-shadow: 0 2px 8px rgba(36, 119, 74, 0.10);
        }

        #dataTabs button.border-blue-500 {
            background: linear-gradient(90deg, #24774a 0%, #24774a 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(36, 119, 74, 0.15);
            border-bottom: 2px solid #24774a;
            z-index: 2;
        }

        #dataTabs button:focus {
            outline: 2px solid #24774a;
            outline-offset: 2px;
        }

        #dataTabs button:not(.border-blue-500) {
            border-bottom: 2px solid transparent;
        }

        /* Tab container spacing */
        .tab-content {
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.05);
            margin-top: -0.5rem;
        }

        .profile-header {
            background: linear-gradient(135deg, #24774a 0%, #24774a 100%);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .notification-banner {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Header Section -->
    <header class="profile-header text-white">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-start items-start">
                <a href="<?= base_url(); ?>">
                    <div class="flex items-start w-full md:w-auto">
                        <img src="<?= getPengaturan('logo'); ?>" alt="Logo <?= getPengaturan('nama_kampus'); ?>"
                            style="width:auto;margin-top: 4px; border-radius:8px; box-shadow:0 2px 8px rgba(36,119,74,0.18); background:#fff; padding:2px;"
                            class="h-12 mr-4">
                        <div>
                            <span class="text-lg font-semibold"><?= getPengaturan('app_name'); ?></span><br>
                            <span class="text-lg font-semibold"><?= getPengaturan('nama_kampus'); ?></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Breadcrumb & Back Button -->
    <div class="container mx-auto px-4 pt-4 pb-2">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div class="mb-2 md:mb-0">
                <a href="/"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
                </a>
            </div>
            <nav class="flex items-center text-sm text-gray-600" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-gray-500 hover:text-green-700">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a1 1 0 01.894.553l7 14A1 1 0 0117 18H3a1 1 0 01-.894-1.447l7-14A1 1 0 0110 2z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <span class="text-gray-400">/</span>
                    </li>
                    <li class="inline-flex items-center">
                        <span class="text-green-700 font-semibold">Profil Dosen</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Profile Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex flex-col items-center mb-12 fade-in">
            <div
                class="bg-white rounded-2xl shadow-lg p-8 card-shadow w-full max-w-6xl flex flex-col md:flex-row items-center md:items-start gap-12">
                <?php
                $foto = $api->getFoto($id_sdm);
                if ($foto && !filter_var($foto, FILTER_VALIDATE_URL)) {
                    $mime = 'image/jpeg';
                    if (function_exists('finfo_buffer')) {
                        $finfo = finfo_open();
                        $mime = finfo_buffer($finfo, $foto, FILEINFO_MIME_TYPE);
                        finfo_close($finfo);
                    }
                    $src = 'data:' . $mime . ';base64,' . base64_encode($foto);
                } else {
                    $src = $foto ?: 'https://ui-avatars.com/api/?name=' . urlencode($dosen->nama ?? 'Dosen') . '&background=24774a&color=fff';
                }

                // Get detail data
                $detail = $db->fetchSingleRow('view_sdm_details', 'id_sdm', $id_sdm);
               // $nidn = $detail->nidn ?? '-';
               // $nuptk = $detail->nuptk ?? '-';
                // $nip = $detail->nip ?? '-';
                $home_base = $detail->home_base ?? ($dosen->home_base ?? '-');

                // Get bidang ilmu from endpoint
                $bidang_ilmu = '-';
                $bidang_ilmu_data = $api->getBidangIlmu($id_sdm);
                if (is_array($bidang_ilmu_data) && count($bidang_ilmu_data) > 0) {
                    $bidang_ilmu_arr = array_map(function ($item) {
                        return $item['kelompok_bidang'] ?? '-';
                    }, $bidang_ilmu_data);
                    $bidang_ilmu = implode("<br>", array_filter($bidang_ilmu_arr));
                }
                ?>
                <div class="flex-shrink-0 w-full md:w-72 flex justify-center items-center">
                    <img src="<?= $src ?>" alt="<?= $dosen->nama ?? '' ?>"
                        class="w-full h-96 object-cover rounded-2xl border-4 border-blue-100 shadow-md">
                </div>
                <div class="flex-1 text-left">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2"><?= $nama_gelar ?? '' ?></h2>
                    <p class="text-gray-600 mb-2 text-lg font-medium"><?= $dosen->jabatan ?? 'Dosen' ?></p>
                    <div class="mt-2 text-sm">
                        <table class="min-w-full text-left border-separate border-spacing-y-2">
                            <tbody>
                                <tr>
                                    <td class="font-semibold text-gray-700 w-40 align-middle"><i
                                            class="fas fa-id-card text-blue-500 mr-2"></i>NIDN</td>
                                    <td class="text-gray-800 align-middle"><?= $nidn ?></td>
                                </tr>
                                <tr>
                                    <td class="font-semibold text-gray-700 w-40 align-middle"><i
                                            class="fas fa-id-badge text-green-500 mr-2"></i>NUPTK</td>
                                    <td class="text-gray-800 align-middle"><?= $nuptk ?></td>
                                </tr>
                                <tr>
                                    <td class="font-semibold text-gray-700 w-40 align-middle"><i
                                            class="fas fa-id-badge text-blue-700 mr-2"></i>NIP</td>
                                    <td class="text-gray-800 align-middle"><?= $nip ?></td>
                                </tr>
                                <tr>
                                    <td class="font-semibold text-gray-700 w-40 align-middle"><i
                                            class="fas fa-university text-purple-500 mr-2"></i>HomeBase</td>
                                    <td class="text-gray-800 align-middle"><?= $home_base ?></td>
                                </tr>
                                <tr>
                                    <td class="font-semibold text-gray-700 w-40 align-middle"><i
                                            class="fas fa-book text-yellow-500 mr-2"></i>Bidang Ilmu</td>
                                    <td class="text-gray-800 align-middle"><?= $bidang_ilmu ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Research Metrics Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                <h3 class="font-semibold text-xl text-gray-800 mb-4">Research Metrics</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-6 rounded-xl hover:bg-blue-100 transition">

                        <?php if ($id_sinta != 'Nothing') {
                            ?>
                            <p class="font-bold text-blue-700"><a target="_blank"
                                    href="https://sinta.kemdikbud.go.id/authors/profile/<?= $id_sinta ?>">SINTA</a></p>
                            <?php
                        } else {
                            ?>
                            <p class="font-bold text-blue-700">SINTA</p>
                            <?php
                        } ?>
                        <p class="text-gray-600">Skor: <?= $sinta ?></p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-xl hover:bg-green-100 transition">
                        <p class="font-bold text-green-700">Scopus</p>
                        <p class="text-gray-600">H-index: <?= $scopus_hindex ?></p>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-xl hover:bg-purple-100 transition">
                        <p class="font-bold text-purple-700">Scholar</p>
                        <p class="text-gray-600">H-index: <?= $scholar_hindex ?></p>
                    </div>
                </div>
            </div>

            <!-- Today's Schedule Card -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Riwayat Pendidikan Dosen (Timeline) -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                    <h3 class="font-semibold text-xl text-pink-600 mb-4 flex items-center">
                        <i class="fas fa-graduation-cap text-pink-500 mr-2"></i> Riwayat Pendidikan Dosen
                    </h3>
                    <?php
                    $pendidikan_rows = $db->query("SELECT jenjang_pendidikan, datas FROM tb_ref_sdm_pendidikan WHERE id_sdm='$id_sdm' AND jenjang_pendidikan IN ('S1','S2','S3')");
                    // Sort ascending by tahun_lulus
                    $pendidikan_sorted = [];
                    foreach ($pendidikan_rows as $row) {
                        $data = json_decode($row->datas, true);
                        $tahun = $data['tahun_lulus'] ?? '';
                        $pendidikan_sorted[] = [
                            'jenjang' => $data['jenjang_pendidikan'] ?? $row->jenjang_pendidikan ?? '-',
                            'tahun' => $tahun,
                            'pt' => $data['nama_perguruan_tinggi'] ?? '-',
                            'bidang' => $data['bidang_studi'] ?? '',
                            'gelar' => $data['gelar_akademik'] ?? ''
                        ];
                    }
                    usort($pendidikan_sorted, function ($a, $b) {
                        return ($a['tahun'] ?? '') <=> ($b['tahun'] ?? '');
                    });
                    ?>
                    <div class="relative">
                        <div class="absolute left-4 top-0 w-0.5 h-full bg-pink-200"></div>
                        <ul class="space-y-6 ml-8">
                            <?php if (count($pendidikan_sorted) > 0) {
                                foreach ($pendidikan_sorted as $item) { ?>
                                    <li class="relative flex items-center">
                                        <span
                                            class="absolute -left-4 w-3 h-3 bg-pink-500 rounded-full border-2 border-white"></span>
                                        <span
                                            class="bg-pink-100 text-pink-600 text-xs font-bold mr-3 px-3 py-1 rounded-full flex items-center">
                                            <i class="fas fa-graduation-cap mr-1"></i> <?= htmlspecialchars($item['jenjang']) ?>
                                        </span>
                                        <span class="text-gray-700">
                                            Tahun <?= htmlspecialchars($item['tahun']) ?>: <?= htmlspecialchars($item['pt']) ?>
                                            <?php if ($item['bidang']) { ?> <br><span class="text-xs text-gray-500">Prodi:
                                                    <?= htmlspecialchars($item['bidang']) ?></span><?php } ?>
                                            <?php if ($item['gelar']) { ?> <br><span class="text-xs text-gray-500">Gelar:
                                                    <?= htmlspecialchars($item['gelar']) ?></span><?php } ?>
                                        </span>
                                    </li>
                                <?php }
                            } else { ?>
                                <li class="text-gray-500">Data pendidikan tidak tersedia.</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- Riwayat Jabatan Fungsional -->
                <!-- Riwayat Jabatan Fungsional (Timeline) -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                    <h3 class="font-semibold text-xl text-green-600 mb-4 flex items-center">
                        <i class="fas fa-briefcase text-green-500 mr-2"></i> Riwayat Jabatan Fungsional
                    </h3>
                    <?php
                    $jabatan_rows = $db->query("SELECT jabatan_fungsional, sk, tanggal_mulai FROM tb_ref_sdm_jabatan_fungsional WHERE id_sdm='$id_sdm'");
                    // Sort ascending by tanggal_mulai
                    $jabatan_sorted = [];
                    foreach ($jabatan_rows as $row) {
                        $jabatan_sorted[] = [
                            'jabatan' => $row->jabatan_fungsional ?? '-',
                            'tmt' => $row->tanggal_mulai ? date('d-m-Y', strtotime($row->tanggal_mulai)) : '-',
                            'sk' => $row->sk ?? '-'
                        ];
                    }
                    usort($jabatan_sorted, function ($a, $b) {
                        return strtotime($a['tmt']) <=> strtotime($b['tmt']);
                    });
                    ?>
                    <div class="relative">
                        <div class="absolute left-4 top-0 w-0.5 h-full bg-green-200"></div>
                        <ul class="space-y-6 ml-8">
                            <?php if (count($jabatan_sorted) > 0) {
                                foreach ($jabatan_sorted as $item) { ?>
                                    <li class="relative flex flex-col items-start">
                                        <span
                                            class="absolute -left-4 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                                        <span
                                            class="font-bold text-green-700 text-lg mb-1"><?= htmlspecialchars($item['jabatan']) ?></span>
                                        <span class="text-xs text-gray-600">TMT: <?= htmlspecialchars($item['tmt']) ?></span>
                                        <span class="text-xs text-gray-600">No SK: <?= htmlspecialchars($item['sk']) ?></span>
                                    </li>
                                <?php }
                            } else { ?>
                                <li class="text-gray-500">Data jabatan fungsional tidak tersedia.</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Teaching Load Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i> Jumlah SKS Mengajar
                </h3>
                <div class="h-80">
                    <canvas id="teachingLoadChart"></canvas>
                </div>
            </div>

            <!-- Student Supervision Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-graduate text-green-500 mr-2"></i> Bimbingan Akademik
                    </h3>
                    <div class="h-64">
                        <canvas id="advisingChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-scroll text-purple-500 mr-2"></i> Bimbingan Tugas Akhir
                    </h3>
                    <div class="h-64">
                        <canvas id="thesisChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Publication Trends -->
            <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-indigo-500 mr-2"></i> Publikasi dalam 5 tahun terakhir
                </h3>
                <div class="h-80">
                    <canvas id="publicationTrendsChart"></canvas>
                </div>
            </div>

            <!-- Recent Publications -->
            <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-book text-yellow-500 mr-2"></i> 5 Publikasi Terakhir
                </h3>
                <div class="space-y-6">
                    <?php if ($publikasi && $publikasi->rowCount() > 0):
                        foreach ($publikasi as $row): ?>
                            <div class="border-l-4 border-blue-500 pl-4 py-3 hover:bg-blue-50 transition rounded">
                                <h4 class="font-medium text-gray-800">"<?= $row->judul ?>"</h4>
                                <p class="text-sm text-gray-500">Tahun : <?= date('Y', strtotime($row->tanggal)) ?></p>
                                <p class="text-sm text-gray-600 mt-1"><?= $row->deskripsi ?? '' ?></p>
                            </div>
                        <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Additional Data Cards -->
            <!-- Tabbed DataTable Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 card-shadow fade-in">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-database text-blue-500 mr-2"></i> Data Kegiatan Dosen
                </h3>
                <div class="mb-4 border-b border-gray-200">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="dataTabs" role="tablist">
                        <li class="mr-2"><button class="inline-block p-4 border-b-2 rounded-t-lg" id="publikasi-tab"
                                onclick="showTab('publikasi')">Publikasi</button></li>
                        <li class="mr-2"><button class="inline-block p-4 border-b-2 rounded-t-lg" id="pengabdian-tab"
                                onclick="showTab('pengabdian')">Pengabdian</button></li>
                        <li class="mr-2"><button class="inline-block p-4 border-b-2 rounded-t-lg" id="ki-tab"
                                onclick="showTab('ki')">KHI/Paten</button></li>
                        <li class="mr-2"><button class="inline-block p-4 border-b-2 rounded-t-lg" id="penelitian-tab"
                                onclick="showTab('penelitian')">Penelitian</button></li>
                    </ul>
                </div>
                <div id="publikasi" class="tab-content">
                    <div class="overflow-x-auto">
                        <table id="publikasiTable"
                            class="min-w-full text-sm border border-gray-200 rounded-lg shadow-sm">
                            <thead>
                                <tr class="bg-blue-50 text-blue-700">
                                    <th class="px-2 py-2">Judul</th>
                                    <th class="px-2 py-2">Jenis</th>
                                    <th class="px-2 py-2">Tanggal</th>
                                    <th class="px-2 py-2">Asal Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($publikasi_all as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row->judul) ?></td>
                                        <td><?= htmlspecialchars($row->jenis_publikasi) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                                        <td><?= htmlspecialchars($row->asal_data) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="pengabdian" class="tab-content hidden">
                    <div class="overflow-x-auto">
                        <table id="pengabdianTable"
                            class="min-w-full text-sm border border-gray-200 rounded-lg shadow-sm">
                            <thead>
                                <tr class="bg-green-50 text-green-700">
                                    <th class="px-2 py-2">Judul</th>
                                    <th class="px-2 py-2">Tahun</th>
                                    <th class="px-2 py-2">Lama Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pengabdian && $pengabdian->rowCount() > 0) {
                                    foreach ($pengabdian as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row->judul) ?></td>
                                            <td><?= htmlspecialchars($row->tahun_pelaksanaan ?? $row->tahun) ?></td>
                                            <td><?= htmlspecialchars($row->lama_kegiatan ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="ki" class="tab-content hidden">
                    <div class="overflow-x-auto">
                        <table id="kiTable" class="min-w-full text-sm border border-gray-200 rounded-lg shadow-sm">
                            <thead>
                                <tr class="bg-yellow-50 text-yellow-700">
                                    <th class="px-2 py-2">Judul</th>
                                    <th class="px-2 py-2">Jenis</th>

                                    <th class="px-2 py-2">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($ki->rowCount() > 0) {
                                    foreach ($ki as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row->judul) ?></td>
                                            <td><?= htmlspecialchars($row->jenis_publikasi ?? '-') ?></td>

                                            <td><?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                                        </tr>
                                    <?php endforeach;

                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="penelitian" class="tab-content hidden">
                    <div class="overflow-x-auto">
                        <table id="penelitianTable"
                            class="min-w-full text-sm border border-gray-200 rounded-lg shadow-sm">
                            <thead>
                                <tr class="bg-purple-50 text-purple-700">
                                    <th class="px-2 py-2">Judul</th>
                                    <th class="px-2 py-2">Tahun</th>
                                    <th class="px-2 py-2">Lama Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $penelitian = $db->query("SELECT * FROM tb_data_penelitian WHERE id_sdm='$id_sdm' ORDER BY tahun_pelaksanaan DESC");
                                if ($penelitian->rowCount() > 0) {
                                    foreach ($penelitian as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row->judul) ?></td>
                                            <td><?= htmlspecialchars($row->tahun_pelaksanaan) ?></td>
                                            <td><?= htmlspecialchars($row->lama_kegiatan) ?></td>
                                        </tr>
                                    <?php endforeach;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white py-1" style="background:#24774a;">
        <div class="container mx-auto">
            <div class="border-t border-gray-700 mt-6 pt-4 text-center text-white text-sm">
                <p>&copy; <?php echo date('Y'); ?> <?= getPengaturan('nama_kampus'); ?></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Teaching Load Chart from $api->getPengajaran($id_sdm)
        <?php
        $pengajaran = $api->getPengajaran($id_sdm);
        $sks_by_semester = [];
        $semester_order = [];
        if (is_array($pengajaran)) {
            foreach ($pengajaran as $row) {
                $semester = $row['semester'] ?? '-';
                $id_smt = $row['id_smt'] ?? $semester;
                $sks = floatval($row['sks_mata_kuliah'] ?? $row['sks'] ?? 0);
                if (!isset($sks_by_semester[$semester]))
                    $sks_by_semester[$semester] = 0;
                $sks_by_semester[$semester] += $sks;
                $semester_order[$semester] = $id_smt;
            }
        }
        // Sort semesters by id_smt
        if (count($semester_order) > 0) {
            uksort($sks_by_semester, function ($a, $b) use ($semester_order) {
                return strnatcmp($semester_order[$a], $semester_order[$b]);
            });
        }
        // Ambil 8 semester terakhir saja
        $semesters = array_slice(array_keys($sks_by_semester), -8, 8);
        $sks_values = array_slice(array_values($sks_by_semester), -8, 8);
        ?>
        const teachingLoadCtx = document.getElementById('teachingLoadChart').getContext('2d');
        const teachingLoadChart = new Chart(teachingLoadCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo "'" . implode("','", $semesters) . "'"; ?>],
                datasets: [
                    {
                        label: 'Total SKS',
                        data: [<?php echo implode(',', $sks_values); ?>],
                        backgroundColor: 'rgba(33, 148, 72, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Credit Hours'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Semester'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            },
            // plugins: [ChartDataLabels]
        });

        // Academic Advising Chart
        <?php
        // Get bimbingan mahasiswa data from API
        $bimbingan_mahasiswa = $api->getBimbinganMahasiswa($id_sdm);
        $bimbingan_by_semester = [];
        $semester_order = [];
        if (is_array($bimbingan_mahasiswa)) {
            foreach ($bimbingan_mahasiswa as $row) {
                if (($row['jenis_bimbingan'] ?? '') === 'Bimbingan akademis') {
                    $semester = $row['semester'] ?? '-';
                    $id_smt = $row['id_smt'] ?? $semester;
                    if (!isset($bimbingan_by_semester[$semester]))
                        $bimbingan_by_semester[$semester] = 0;
                    $bimbingan_by_semester[$semester]++;
                    $semester_order[$semester] = $id_smt;
                }
            }
        }
        // Sort by id_smt if available
        if (count($semester_order) > 0) {
            uksort($bimbingan_by_semester, function ($a, $b) use ($semester_order) {
                return strnatcmp($semester_order[$a], $semester_order[$b]);
            });
        }
        $advising_labels = array_keys($bimbingan_by_semester);
        $advising_data = array_values($bimbingan_by_semester);
        ?>
        const advisingCtx = document.getElementById('advisingChart').getContext('2d');
        const advisingChart = new Chart(advisingCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo "'" . implode("','", $advising_labels) . "'"; ?>],
                datasets: [
                    {
                        label: 'Jumlah Bimbngan',
                        data: [<?php echo implode(',', $advising_data); ?>],
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Semester'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Thesis Supervision Chart
        <?php
        // Use bimbingan_mahasiswa from API, filter for jenis_bimbingan == 'Skripsi'
        $thesis_by_semester = [];
        $thesis_semester_order = [];
        if (is_array($bimbingan_mahasiswa)) {
            foreach ($bimbingan_mahasiswa as $row) {
                if (($row['jenis_bimbingan'] ?? '') === 'Skripsi') {
                    $semester = $row['semester'] ?? '-';
                    $id_smt = $row['id_smt'] ?? $semester;
                    if (!isset($thesis_by_semester[$semester]))
                        $thesis_by_semester[$semester] = 0;
                    $thesis_by_semester[$semester]++;
                    $thesis_semester_order[$semester] = $id_smt;
                }
            }
        }
        // Sort by id_smt if available
        if (count($thesis_semester_order) > 0) {
            uksort($thesis_by_semester, function ($a, $b) use ($thesis_semester_order) {
                return strnatcmp($thesis_semester_order[$a], $thesis_semester_order[$b]);
            });
        }
        $thesis_labels = array_keys($thesis_by_semester);
        $thesis_data = array_values($thesis_by_semester);
        ?>
        const thesisCtx = document.getElementById('thesisChart').getContext('2d');
        const thesisChart = new Chart(thesisCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo "'" . implode("','", $thesis_labels) . "'"; ?>],
                datasets: [
                    {
                        label: 'Total Bimbingan TA',
                        data: [<?php echo implode(',', $thesis_data); ?>],
                        backgroundColor: 'rgba(139, 92, 246, 0.7)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Semester'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Publication Trends Chart
        const pubTrendsCtx = document.getElementById('publicationTrendsChart').getContext('2d');
        const pubTrendsChart = new Chart(pubTrendsCtx, {
            type: 'line',
            data: {
                labels: [<?php $years = array_reverse(array_keys($trend));
                echo "'" . implode("','", $years) . "'"; ?>],
                datasets: [
                    <?php
                    // Color palette for lines
                    $colors = [
                        'rgba(59,130,246,1)',
                        'rgba(16,185,129,1)',
                        'rgba(139,92,246,1)',
                        'rgba(245,158,11,1)',
                        'rgba(255,99,132,1)',
                        'rgba(34,197,94,1)',
                        'rgba(251,191,36,1)',
                        'rgba(239,68,68,1)',
                        'rgba(168,85,247,1)',
                        'rgba(20,184,166,1)'
                    ];
                    $bgcolors = [
                        'rgba(59,130,246,0.1)',
                        'rgba(16,185,129,0.1)',
                        'rgba(139,92,246,0.1)',
                        'rgba(245,158,11,0.1)',
                        'rgba(255,99,132,0.1)',
                        'rgba(34,197,94,0.1)',
                        'rgba(251,191,36,0.1)',
                        'rgba(239,68,68,0.1)',
                        'rgba(168,85,247,0.1)',
                        'rgba(20,184,166,0.1)'
                    ];
                    $i = 0;
                    foreach ($kategori_list as $kat) {
                        $color = $colors[$i % count($colors)];
                        $bgcolor = $bgcolors[$i % count($bgcolors)];
                        echo "{\n";
                        echo "label: '" . addslashes($kat) . "',\n";
                        echo "data: [";
                        foreach (array_reverse($trend) as $tahun => $data) {
                            echo $data[$kat] . ',';
                        }
                        echo "],\n";
                        echo "borderColor: '$color',\n";
                        echo "backgroundColor: '$bgcolor',\n";
                        echo "borderWidth: 2,\n";
                        echo "tension: 0.3\n";
                        echo "},\n";
                        $i++;
                    }
                    ?>
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Publications'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Year'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>

</html>
