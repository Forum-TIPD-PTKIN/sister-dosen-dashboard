<?php
// Initialize dashboard data
$dashboardData = [];
try {
    $dashboardData['sdm'] = $api->getSDM();
    // SDM per Unit Kerja
// ...existing code...
$dashboardData = [];
try {
    // Get jumlah dosen from tb_ref_sdm
    $sql = "SELECT COUNT(*) AS jml FROM view_nama_gelar where view_nama_gelar.nama_dengan_gelar IS NOT NULL";
    $result = $db->fetchCustomSingle($sql);
    $dashboardData['sdm_count'] = ($result && $result->jml > 0) ? (int)$result->jml : 0;

    // Get jumlah penelitian from tb_data_penelitian
    $sql = "SELECT COUNT(*) AS jml FROM tb_data_penelitian";
    $result = $db->fetchCustomSingle($sql);
    $dashboardData['penelitian_count'] = ($result && $result->jml > 0) ? (int)$result->jml : 0;

    // SDM per Unit Kerja
    $dashboardData['unitKerja'] = [];

    // Jabatan Fungsional distribution
    $dashboardData['jabatanFungsional'] = [];
    $sql = "SELECT COUNT(*) AS jml, COALESCE(jafung, 'Lainnya') AS jafung FROM view_sdm_jabatan_fungsional GROUP BY jafung";
    $result = $db->query($sql);
    foreach ($result as $row) {
        $dashboardData['jabatanFungsional'][] = [
            'nama' => $row->jafung,
            'jumlah' => (int)$row->jml
        ];
    }
    // Tingkat Pendidikan
    $dashboardData['jenjangPendidikan'] = [];
    $sql = "SELECT COUNT(*) AS jml, pendidikan FROM view_sdm_pendidikan where  pendidikan is not null GROUP BY pendidikan";
    $result = $db->query($sql);
    foreach ($result as $row) {
        $dashboardData['jenjangPendidikan'][] = [
            'nama' => $row->pendidikan,
            'jumlah' => (int)$row->jml
        ];
    }
    $dashboardData['profilPT'] = $api->getProfilPT();
    // Calculate statistics
    // Publikasi trend per tahun (last 5 years)
    $currentYear = (int)date('Y');
    $years = [];
    $publikasiTrend = [];
    for ($i = 4; $i >= 0; $i--) {
        $year = $currentYear - $i;
        $years[] = $year;
        $sql = "SELECT COUNT(*) AS jml FROM tb_data_publikasi WHERE YEAR(tanggal) = ?";
        $result = $db->fetchCustomSingle($sql, [$year]);
        $publikasiTrend[] = ($result && $result->jml > 0) ? (int)$result->jml : 0;
    }
    $dashboardData['publikasiTrendYears'] = $years;
    $dashboardData['publikasiTrendData'] = $publikasiTrend;
    // Get jumlah publikasi from tb_data_publikasi
    $sql = "SELECT COUNT(*) AS jml FROM tb_data_publikasi";
    $result = $db->fetchCustomSingle($sql);
    $dashboardData['publikasi_count'] = ($result && $result->jml > 0) ? (int)$result->jml : 0;

    // Get jumlah pengabdian from tb_data_pengabdian
    $sql = "SELECT COUNT(*) AS jml FROM tb_data_pengabdian";
    $result = $db->fetchCustomSingle($sql);
    $dashboardData['pengabdian_count'] = ($result && $result->jml > 0) ? (int)$result->jml : 0;

    $stats = [
        'totalSDM' => $dashboardData['sdm_count'],
        'totalPenelitian' => $dashboardData['penelitian_count'],
        'totalPublikasi' => $dashboardData['publikasi_count'],
        'totalPengabdian' => $dashboardData['pengabdian_count']
    ];
} catch (Exception $e) {
    $error = $e->getMessage();
    $dashboardData = [];
    $stats = ['totalSDM' => 0, 'totalPenelitian' => 0, 'totalPublikasi' => 0, 'totalPengabdian' => 0];
}
    $dashboardData['sdm'] = $api->getSDM();
    
    // SDM per Unit Kerja
    $dashboardData['unitKerja'] = [];
    $sql = "SELECT COUNT(*) AS jml, unit_kerja FROM tb_ref_sdm_penugasan GROUP BY unit_kerja";
    $result = $db->query($sql);
    foreach ($result as $row) {
        $dashboardData['unitKerja'][] = [
            'nama' => str_replace('Program Studi ', '', $row->unit_kerja),
            'jumlah' => (int)$row->jml
        ];
    }
    
    // Jabatan Fungsional distribution
    $dashboardData['jabatanFungsional'] = [];
    $sql = "SELECT COUNT(*) AS jml, COALESCE(jafung, 'Lainnya') AS jafung FROM view_sdm_jabatan_fungsional GROUP BY jafung";
    $result = $db->query($sql);
    foreach ($result as $row) {
        $dashboardData['jabatanFungsional'][] = [
            'nama' => $row->jafung,
            'jumlah' => (int)$row->jml
        ];
    }
    
    // Tingkat Pendidikan
    $dashboardData['jenjangPendidikan'] = [];
    $sql = "SELECT COUNT(*) AS jml, pendidikan FROM view_sdm_pendidikan where pendidikan is not null GROUP BY pendidikan";
    $result = $db->query($sql);
    foreach ($result as $row) {
        $dashboardData['jenjangPendidikan'][] = [
            'nama' => $row->pendidikan,
            'jumlah' => (int)$row->jml
        ];
    }
    
    $dashboardData['profilPT'] = $api->getProfilPT();
    
    // Calculate statistics
    // ...existing code...
} catch (Exception $e) {
    $error = $e->getMessage();
    $dashboardData = [];
    $stats = ['totalSDM' => 0, 'totalPenelitian' => 0, 'totalPublikasi' => 0, 'totalPengabdian' => 0];
}

function getStatusBadgeColor($status) {
    return match (strtolower($status)) {
        'aktif' => 'success',
        'non-aktif' => 'danger',
        'cuti' => 'warning',
        default => 'secondary',
    };
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=getPengaturan('app_name');?> - <?=getPengaturan('nama_kampus');?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/flatly/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        body { background: #f8f9fa; color: #181c24; font-family: 'Inter', 'Segoe UI', Arial, sans-serif; }
        .navbar { background: #fff !important; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-bottom: 1px solid #e5e7eb; }
        .navbar-brand, .navbar-text { color: #181c24 !important; font-weight: 600; font-size: 1.08rem; letter-spacing: 0.2px; }
        .btn-outline-light { border-radius: 20px; border: 1px solid #e5e7eb; color: #181c24; font-weight: 600; }
        .card { border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); border: none; background: #fff; color: #181c24; }
        .card-header { background: transparent; border-bottom: none; font-weight: 700; font-size: 1.15rem; color: #181c24; letter-spacing: 0.3px; }
        .card-body { padding: 1.5rem; }
        .table { border-radius: 12px; overflow: hidden; background: #fff; color: #181c24; font-size: 1.05rem; }
        .table th { background: #eaf0fa; color: #181c24; font-weight: 700; font-size: 1.07rem; letter-spacing: 0.2px; }
        .table td { background: #fff; color: #181c24; font-weight: 500; }
        .badge { border-radius: 12px; font-size: 1em; padding: 0.5em 1em; font-weight: 600; letter-spacing: 0.2px; }
        .chart-container { min-height: 400px; background: #eaf0fa; border-radius: 16px; padding: 1.5rem; color: #181c24; }
        .alert { border-radius: 14px; background: #eaf0fa; color: #181c24; font-weight: 600; font-size: 1.05rem; }
        .pagination .page-link { background: #fff; color: #181c24; border: 1px solid #e5e7eb; font-weight: 600; }
        .pagination .page-item.active .page-link { background: #4e73df; color: #fff; border: 1px solid #4e73df; }
        @media (max-width: 768px) { .card-body, .chart-container { padding: 1rem; } }
    </style>
     <link rel="shortcut icon" href="<?=getPengaturan('favicon');?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg" style="background: #24774a !important; box-shadow: 0 2px 8px rgba(36,119,74,0.08); border-bottom: 1px solid #24774a;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <img src="<?=getPengaturan('logo');?>" alt="UIN Syekh Ali Hasan Ahmad Addary Padangsidimpuan" style="height:47px; width:auto; border-radius:8px; box-shadow:0 2px 8px rgba(36,119,74,0.18); background:#fff; padding:2px;">
                <div class="d-flex flex-column">
                    <span style="font-weight:700; font-size:1.2rem; color:#fff; letter-spacing:0.5px; text-shadow:0 1px 4px rgba(36,119,74,0.18);"><?=getPengaturan('app_name');?></span>
                    <span style="font-size:1rem; color:#eaf0fa; font-weight:600; letter-spacing:0.3px; text-shadow:0 1px 4px rgba(36,119,74,0.18);"><?=getPengaturan('nama_kampus');?></span>
                </div>
            </a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Error: <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row mb-4 g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-2" style="letter-spacing:0.5px;">Total DOSEN</div>
                                <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format($stats['totalSDM']); ?></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-users fa-2x" style="color:#4e73df33;"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-2" style="letter-spacing:0.5px;">Total Penelitian</div>
                                <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format($stats['totalPenelitian']); ?></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-flask fa-2x" style="color:#1cc88a33;"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-2" style="letter-spacing:0.5px;">Total Publikasi</div>
                                <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format($stats['totalPublikasi']); ?></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-book fa-2x" style="color:#36b9cc33;"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-2" style="letter-spacing:0.5px;">Total Pengabdian</div>
                                <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format($stats['totalPengabdian']); ?></div>
                            </div>
                            <div class="col-auto"><i class="fas fa-hands-helping fa-2x" style="color:#f6c23e33;"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6 col-lg-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-table" style="color:#4e73df;"></i>
                        <span>Distribusi DOSEN per Unit Kerja</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="unitKerjaTable">
                                <thead>
                                    <tr>
                                        <th>Unit Kerja</th>
                                        <th>Jumlah DOSEN</th>
                                    </tr>
                                </thead>
                                <tbody id="unitKerjaTableBody">
                                    <?php
                                        $sql = "SELECT COUNT(*) AS jml, unit_kerja FROM tb_ref_sdm_penugasan GROUP BY unit_kerja";
    $result = $db->query($sql);
    ?>
                                    <?php foreach ($result as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(str_replace('Program Studi ', '', $row->unit_kerja)); ?></td>
                                            <td><?php echo number_format((int)$row->jml); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                            <nav><ul class="pagination justify-content-center" id="unitKerjaPagination"></ul></nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-chart-bar" style="color:#1cc88a;"></i> Distribusi Jabatan Fungsional
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="padding:0; background:none; min-height:400px;">
        <div id="jabatanHighchart" style="height:350px; width:100%; max-width:100%; margin:0 auto;"></div>
    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6 col-lg-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-graduation-cap" style="color:#36b9cc;"></i> Tingkat Pendidikan SDM
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="padding:0; background:none; min-height:400px;">
        <div id="pendidikanHighchart" style="height:350px; width:100%; max-width:100%; margin:0 auto;"></div>
    </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-chart-line" style="color:#e74a3b;"></i> Trend Publikasi per Tahun
                    </div>
                    <div class="card-body">
        <div class="chart-container" style="min-height:400px; background:none;">
            <div id="publikasiTrendHighchart" style="height:400px; width:100%; max-width:100%; margin:0 auto;"></div>
        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-12">
                <div class="card mb-4">

                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="sdmTable">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NUPTK</th>
                                        <th>Pendidikan Terakhir</th>
                                        <th>Home Base</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT view_nama_gelar.nama_dengan_gelar, vd.nuptk, vd.pendidikan_terakhir, vd.home_base, vd.id_sdm FROM view_sdm vd inner join view_nama_gelar using(id_sdm)";
// Add NIP to select
$sql = "SELECT view_nama_gelar.nama_dengan_gelar, vd.nuptk, vd.nip, vd.pendidikan_terakhir, vd.home_base, vd.id_sdm FROM view_sdm vd inner join view_nama_gelar using(id_sdm) where view_nama_gelar.nama_dengan_gelar IS NOT NULL order by jafung desc,pendidikan_terakhir desc";
$result = $db->query($sql);
foreach ($result as $row): ?>
    <tr>
        <td><?php echo htmlspecialchars($row->nama_dengan_gelar); ?></td>
        <td><?php echo htmlspecialchars($row->nuptk); ?></td>
        <td><?php echo htmlspecialchars($row->pendidikan_terakhir); ?></td>
        <td><?php echo htmlspecialchars(str_replace('Program Studi ', '', $row->home_base)); ?></td>
        <td>
            <a class="btn btn-sm btn-outline-primary rounded-pill px-3" href="<?=base_url();?>detail/<?php echo $row->id_sdm; ?>">
                <i class="fas fa-eye"></i> View
    </a>
        </td>
    </tr>
<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    
    <script>
        $(document).ready(function() {
  $('#unitKerjaTable').DataTable({
    lengthMenu: [[7, 10, 25, -1], [5, 10, 25, "All"]],
    pageLength: 7
  });
  $('#sdmTable').DataTable({
    'sorting': false,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    pageLength: 10
  });
  initializeCharts();
  renderPendidikanHighchart();
  renderJabatanHighchart();
});

        const dashboardData = <?php echo json_encode($dashboardData); ?>;
        const chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#9f7aea', '#fd7e14', '#20c997', '#6c757d', '#f8f9fa'];


        function escapeHTML(str) {
            return String(str).replace(/[&<>'"]/g, tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag));
        }

        function initializeCharts() {
            renderPendidikanHighchart();
            renderJabatanHighchart();
            renderPublikasiTrendHighchart();
        }

        function renderJabatanHighchart() {
    const jabatanData = <?php echo json_encode($dashboardData['jabatanFungsional'] ?? []); ?>;
    const pieData = jabatanData.map(j => ({ name: j.nama, y: j.jumlah }));
    Highcharts.chart('jabatanHighchart', {
        chart: {
            type: 'pie',
            height: 350,
            backgroundColor: null,
            spacing: [10, 10, 10, 10],
            style: { width: '100%' }
        },
        colors: [
            '#43ea7a', // modern green
            '#1cc88a', // teal green
            '#20c997', // emerald
            '#6ee7b7', // light green
            '#2ecc40', // vivid green
            '#27ae60', // deep green
            '#81c784', // soft green
            '#00b894', // turquoise green
            '#00e676', // neon green
            '#388e3c'  // dark green
        ],
        title: {
            text: 'Distribusi Jabatan Fungsional'
        },
        tooltip: {
            enabled: true,
            style: { color: '#181c24', fontWeight: 'bold' },
            pointFormat: '<span style="color:#181c24"><b>{point.name}</b>: {point.y} orang ({point.percentage:.1f}%)</span>'
        },
        
plotOptions: {
    pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
            style: {
                color: '#000000', // Change this to your desired text color
                fontWeight: 'bold'
            }
        },
        showInLegend: false
    }
},

        series: [{
            type: 'pie',
            name: 'Jumlah',
            data: pieData
        }]
    });
}

        function renderPendidikanHighchart() {
    // Get pendidikan data from PHP
    const pendidikanData = <?php echo json_encode($dashboardData['jenjangPendidikan'] ?? []); ?>;
    const pieData = pendidikanData.map(p => ({ name: p.nama, y: p.jumlah }));
    Highcharts.chart('pendidikanHighchart', {
        chart: {
            type: 'pie',
            height: 350,
            backgroundColor: null,
            spacing: [10, 10, 10, 10],
            style: { width: '100%' }
        },
        colors: [
            '#43ea7a', // modern green
            '#1cc88a', // teal green
            '#20c997', // emerald
            '#6ee7b7', // light green
            '#2ecc40', // vivid green
            '#27ae60', // deep green
            '#81c784', // soft green
            '#00b894', // turquoise green
            '#00e676', // neon green
            '#388e3c'  // dark green
        ],
        title: {
            text: 'Tingkat Pendidikan DOSEN'
        },
       
        tooltip: {
            enabled: true,
            pointFormat: '<b>{point.name}</b>: {point.y} orang ({point.percentage:.1f}%)'
        },
       plotOptions: {
    pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
            enabled: true,
            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
            style: {
                color: '#000000', // Change this to your desired text color
                fontWeight: 'bold'
            }
        },
        showInLegend: false
    }
},
        series: [{
            type: 'pie',
            name: 'Jumlah',
            data: pieData
        }]
    });
}

        function renderPublikasiTrendHighchart() {
            const years = <?php echo json_encode($dashboardData['publikasiTrendYears'] ?? []); ?>;
            const data = <?php echo json_encode($dashboardData['publikasiTrendData'] ?? []); ?>;
            Highcharts.chart('publikasiTrendHighchart', {
                chart: {
                    type: 'line',
                    height: 400,
                    backgroundColor: null,
                    style: { width: '100%' }
                },
                colors: ['#43ea7a'],
                title: {
                    text: 'Trend Publikasi per Tahun',
                    style: { color: '#181c24', fontWeight: 'bold', fontSize: '1.2rem' }
                },
               
                xAxis: {
                    categories: years,
                    title: { text: 'Tahun', style: { color: '#181c24', fontWeight: 'bold' } },
                    labels: { style: { color: '#181c24', fontWeight: 'bold', fontSize: '14px' } },
                    gridLineColor: '#eaf0fa'
                },
                yAxis: {
                    min: 0,
                    title: { text: 'Jumlah Publikasi', style: { color: '#181c24', fontWeight: 'bold' } },
                    labels: { style: { color: '#181c24', fontWeight: 'bold', fontSize: '14px' } },
                    gridLineColor: '#eaf0fa'
                },
                legend: {
                    enabled: true,
                    align: 'center',
                    verticalAlign: 'bottom',
                    itemStyle: { color: '#181c24', fontWeight: 'bold', fontSize: '15px' }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    borderColor: '#27ae60',
                    borderWidth: 2,
                    style: { color: '#181c24', fontWeight: 'bold' },
                    headerFormat: '<span style="color:#43ea7a;font-weight:bold">{point.key}</span><br/>',
                    pointFormat: '<span style="color:#181c24">Jumlah: <b>{point.y}</b></span>'
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true,
                            style: { color: '#181c24', fontWeight: 'bold', fontSize: '14px' },
                            format: '{y}'
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                    name: 'Publikasi',
                    data: data,
                    color: '#43ea7a',
                    lineWidth: 4,
                    marker: {
                        enabled: true,
                        radius: 6,
                        fillColor: '#1cc88a',
                        lineColor: '#27ae60',
                        lineWidth: 2,
                        states: { hover: { radius: 9 } }
                    }
                }]
            });
        }


        async function handleLogout() {
            if (!confirm('Are you sure you want to logout?')) return;
            try {
                const response = await fetch('auth/logout_process.php', { method: 'POST', headers: { 'Content-Type': 'application/json' } });
                const data = await response.json();
                if (data.success) window.location.href = 'logout.php?processed=true';
                else alert('Logout failed: ' + data.message);
            } catch (error) {
                console.error('Logout error:', error);
                window.location.href = 'logout.php?processed=true';
            }
        }
    </script>
    <footer class="mt-5 py-3" style="background:#24774a; color:#fff; text-align:center; font-size:1.05rem; letter-spacing:0.2px; box-shadow:0 -2px 8px rgba(36,119,74,0.08);">
        <div class="container">
            &copy; <?php echo date('Y'); ?> <?=getPengaturan('nama_kampus');?>
        </div>
    </footer>
</body>
</html>