<?php
function adminScalar($db, $sql, $default = 0)
{
    $row = $db->fetchCustomSingle($sql);
    if (!$row) {
        return $default;
    }

    $values = get_object_vars($row);
    $value = reset($values);

    return $value !== null ? $value : $default;
}

function adminRows($db, $sql)
{
    $result = $db->query($sql);
    if (!is_array($result) && !$result instanceof Traversable) {
        return [];
    }

    $rows = [];
    foreach ($result as $row) {
        $rows[] = $row;
    }

    return $rows;
}

function adminTrend($db, $sql)
{
    $rows = adminRows($db, $sql);
    $data = [];

    foreach ($rows as $row) {
        if ($row->tahun === null || $row->tahun === '') {
            continue;
        }
        $data[] = [
            'tahun' => (string) $row->tahun,
            'jumlah' => (int) $row->jumlah,
        ];
    }

    usort($data, function ($a, $b) {
        return $a['tahun'] <=> $b['tahun'];
    });

    return [
        'labels' => array_column($data, 'tahun'),
        'values' => array_column($data, 'jumlah'),
    ];
}

function adminDistribution($rows, $labelKey)
{
    $labels = [];
    $values = [];

    foreach ($rows as $row) {
        $labels[] = $row->{$labelKey} ?: 'Lainnya';
        $values[] = (int) $row->jumlah;
    }

    return ['labels' => $labels, 'values' => $values];
}

$summary = [
    'sdm' => (int) adminScalar($db, "SELECT COUNT(*) FROM tb_ref_sdm"),
    'publikasi' => (int) adminScalar($db, "SELECT COUNT(*) FROM tb_data_publikasi"),
    'penelitian' => (int) adminScalar($db, "SELECT COUNT(*) FROM tb_data_penelitian"),
    'pengabdian' => (int) adminScalar($db, "SELECT COUNT(*) FROM tb_data_pengabdian"),
    'hki' => (int) adminScalar($db, "SELECT COUNT(*) FROM tb_data_hki"),
    'unit' => (int) adminScalar($db, "SELECT COUNT(DISTINCT unit_kerja) FROM tb_ref_sdm_penugasan"),
];

$updatedAt = adminScalar($db, "SELECT MAX(updated_at) FROM tb_ref_sdm", null);
$lastSyncText = $updatedAt ? date('d M Y H:i', strtotime($updatedAt)) : 'Belum ada data';

$publikasiTrend = adminTrend($db, "SELECT YEAR(tanggal) AS tahun, COUNT(*) AS jumlah FROM tb_data_publikasi GROUP BY YEAR(tanggal) ORDER BY tahun DESC LIMIT 8");
$penelitianTrend = adminTrend($db, "SELECT tahun_pelaksanaan AS tahun, COUNT(*) AS jumlah FROM tb_data_penelitian GROUP BY tahun_pelaksanaan ORDER BY tahun DESC LIMIT 8");

$jabatan = adminDistribution(
    adminRows($db, "SELECT COALESCE(jafung, 'Lainnya') AS label, COUNT(*) AS jumlah FROM view_sdm_jabatan_fungsional GROUP BY COALESCE(jafung, 'Lainnya') ORDER BY jumlah DESC"),
    'label'
);

$statusPegawai = adminDistribution(
    adminRows($db, "SELECT COALESCE(nama_status_pegawai, 'Lainnya') AS label, COUNT(*) AS jumlah FROM tb_ref_sdm GROUP BY COALESCE(nama_status_pegawai, 'Lainnya') ORDER BY jumlah DESC"),
    'label'
);

$unitRows = adminRows($db, "SELECT unit_kerja, COUNT(*) AS jumlah FROM tb_ref_sdm_penugasan GROUP BY unit_kerja ORDER BY jumlah DESC, unit_kerja ASC LIMIT 8");
$unitLabels = [];
$unitValues = [];
foreach ($unitRows as $row) {
    $unitLabels[] = str_replace('Program Studi ', '', $row->unit_kerja);
    $unitValues[] = (int) $row->jumlah;
}

$cards = [
    ['label' => 'Dosen', 'value' => $summary['sdm'], 'icon' => 'fa-users', 'tone' => 'green'],
    ['label' => 'Publikasi', 'value' => $summary['publikasi'], 'icon' => 'fa-book', 'tone' => 'blue'],
    ['label' => 'Penelitian', 'value' => $summary['penelitian'], 'icon' => 'fa-flask', 'tone' => 'teal'],
    ['label' => 'Pengabdian', 'value' => $summary['pengabdian'], 'icon' => 'fa-handshake-o', 'tone' => 'orange'],
];
?>

<style>
.admin-dashboard-modern {
  color: #17212f;
}
.admin-dashboard-modern .dashboard-hero {
  background: linear-gradient(135deg, #126b45 0%, #1f8a5b 52%, #32a172 100%);
  border-radius: 8px;
  color: #fff;
  margin-bottom: 20px;
  overflow: hidden;
  padding: 26px 28px;
  position: relative;
}
.admin-dashboard-modern .dashboard-hero:after {
  background: rgba(255,255,255,.12);
  border-radius: 999px;
  content: "";
  height: 190px;
  position: absolute;
  right: -55px;
  top: -70px;
  width: 190px;
}
.admin-dashboard-modern .hero-title {
  font-size: 26px;
  font-weight: 700;
  letter-spacing: .2px;
  margin: 0 0 8px;
}
.admin-dashboard-modern .hero-subtitle {
  color: rgba(255,255,255,.86);
  font-size: 14px;
  margin: 0;
}
.admin-dashboard-modern .hero-meta {
  background: rgba(255,255,255,.14);
  border: 1px solid rgba(255,255,255,.24);
  border-radius: 8px;
  display: inline-block;
  margin-top: 16px;
  padding: 9px 12px;
}
.admin-dashboard-modern .stat-card,
.admin-dashboard-modern .panel-card {
  background: #fff;
  border: 1px solid #e9eef5;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(23,33,47,.06);
  margin-bottom: 20px;
}
.admin-dashboard-modern .stat-card {
  min-height: 118px;
  padding: 20px;
}
.admin-dashboard-modern .stat-card .stat-label {
  color: #6b778c;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: .7px;
  margin-bottom: 8px;
  text-transform: uppercase;
}
.admin-dashboard-modern .stat-card .stat-value {
  color: #17212f;
  font-size: 30px;
  font-weight: 700;
  line-height: 1;
}
.admin-dashboard-modern .stat-icon {
  align-items: center;
  border-radius: 8px;
  display: flex;
  float: right;
  height: 44px;
  justify-content: center;
  width: 44px;
}
.admin-dashboard-modern .tone-green { background: #e7f7ee; color: #147347; }
.admin-dashboard-modern .tone-blue { background: #e9f2ff; color: #286fc7; }
.admin-dashboard-modern .tone-teal { background: #e3f8f7; color: #10847e; }
.admin-dashboard-modern .tone-orange { background: #fff2df; color: #c86b00; }
.admin-dashboard-modern .panel-card .panel-heading {
  background: transparent;
  border-bottom: 1px solid #eef2f7;
  padding: 18px 20px;
}
.admin-dashboard-modern .panel-title {
  color: #17212f;
  font-size: 16px;
  font-weight: 700;
}
.admin-dashboard-modern .panel-title .fa {
  color: #178957;
  margin-right: 8px;
}
.admin-dashboard-modern .panel-body {
  padding: 18px 20px 20px;
}
.admin-dashboard-modern .chart-box {
  min-height: 320px;
}
.admin-dashboard-modern .mini-summary {
  background: #f7fafc;
  border-radius: 8px;
  margin-bottom: 20px;
  padding: 16px 18px;
}
.admin-dashboard-modern .mini-summary strong {
  color: #17212f;
  display: block;
  font-size: 20px;
}
.admin-dashboard-modern .mini-summary span {
  color: #6b778c;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
}
.admin-dashboard-modern .unit-table > tbody > tr > td,
.admin-dashboard-modern .unit-table > thead > tr > th {
  border-color: #edf1f6;
  vertical-align: middle;
}
.admin-dashboard-modern .unit-table > thead > tr > th {
  color: #667085;
  font-size: 12px;
  letter-spacing: .4px;
  text-transform: uppercase;
}
.admin-dashboard-modern .empty-state {
  color: #7b8794;
  padding: 35px 10px;
  text-align: center;
}
@media (max-width: 767px) {
  .admin-dashboard-modern .dashboard-hero { padding: 22px 18px; }
  .admin-dashboard-modern .hero-title { font-size: 22px; }
}
</style>

<section class="content-header">
  <h1>Dashboard Admin</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_admin();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>

<section class="content admin-dashboard-modern">
  <div class="dashboard-hero">
    <div class="row">
      <div class="col-md-8">
        <h2 class="hero-title">Ringkasan SISTER Dosen</h2>
        <p class="hero-subtitle">Pantau data dosen, publikasi, penelitian, pengabdian, dan referensi akademik dari satu halaman.</p>
        <div class="hero-meta"><i class="fa fa-refresh"></i> Update data terakhir: <?=htmlspecialchars($lastSyncText);?></div>
      </div>
      <div class="col-md-4 text-right hidden-xs hidden-sm">
        <div class="hero-meta"><i class="fa fa-database"></i> <?=number_format($summary['unit']);?> unit kerja aktif</div>
      </div>
    </div>
  </div>

  <div class="row">
    <?php foreach ($cards as $card): ?>
      <div class="col-lg-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-icon tone-<?=$card['tone'];?>"><i class="fa <?=$card['icon'];?>"></i></div>
          <div class="stat-label"><?=$card['label'];?></div>
          <div class="stat-value"><?=number_format($card['value']);?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="row">
    <div class="col-md-8">
      <div class="panel-card">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-line-chart"></i> Tren Kinerja Akademik</h3>
        </div>
        <div class="panel-body">
          <div id="chart-tren-akademik" class="chart-box"></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="mini-summary">
        <span>HKI</span>
        <strong><?=number_format($summary['hki']);?></strong>
      </div>
      <div class="mini-summary">
        <span>Unit Kerja</span>
        <strong><?=number_format($summary['unit']);?></strong>
      </div>
      <div class="panel-card">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-briefcase"></i> Status Pegawai</h3>
        </div>
        <div class="panel-body">
          <div id="chart-status-pegawai" class="chart-box"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="panel-card">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-id-badge"></i> Jabatan Fungsional</h3>
        </div>
        <div class="panel-body">
          <div id="chart-jabatan-fungsional" class="chart-box"></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel-card">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-sitemap"></i> Dosen per Unit Kerja</h3>
        </div>
        <div class="panel-body">
          <div id="chart-unit-kerja" class="chart-box"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="panel-card">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-table"></i> Unit Kerja Teratas</h3>
    </div>
    <div class="panel-body">
      <?php if (empty($unitRows)): ?>
        <div class="empty-state">Belum ada data unit kerja. Jalankan sinkronisasi SISTER terlebih dahulu.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table unit-table">
            <thead>
              <tr>
                <th>Unit Kerja</th>
                <th class="text-right">Jumlah Dosen</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($unitRows as $row): ?>
                <tr>
                  <td><?=htmlspecialchars(str_replace('Program Studi ', '', $row->unit_kerja));?></td>
                  <td class="text-right"><strong><?=number_format((int)$row->jumlah);?></strong></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
(function () {
  var publikasiYears = <?=json_encode($publikasiTrend['labels']);?>;
  var publikasiCounts = <?=json_encode($publikasiTrend['values']);?>;
  var penelitianYears = <?=json_encode($penelitianTrend['labels']);?>;
  var penelitianCounts = <?=json_encode($penelitianTrend['values']);?>;
  var trendYears = Array.from(new Set(publikasiYears.concat(penelitianYears))).sort();

  function alignSeries(years, values) {
    return trendYears.map(function (year) {
      var index = years.indexOf(year);
      return index >= 0 ? values[index] : 0;
    });
  }

  function emptyAware(values) {
    return values && values.length ? values : [0];
  }

  var commonChart = {
    fontFamily: 'Arial, sans-serif',
    toolbar: { show: false }
  };

  new ApexCharts(document.querySelector('#chart-tren-akademik'), {
    chart: Object.assign({ type: 'area', height: 320 }, commonChart),
    colors: ['#178957', '#286fc7'],
    dataLabels: { enabled: false },
    fill: { type: 'gradient', gradient: { opacityFrom: .28, opacityTo: .04 } },
    grid: { borderColor: '#edf1f6' },
    series: [
      { name: 'Publikasi', data: alignSeries(publikasiYears, publikasiCounts) },
      { name: 'Penelitian', data: alignSeries(penelitianYears, penelitianCounts) }
    ],
    stroke: { curve: 'smooth', width: 3 },
    xaxis: { categories: trendYears.length ? trendYears : ['-'] },
    yaxis: { min: 0, labels: { formatter: function (value) { return Math.round(value); } } }
  }).render();

  new ApexCharts(document.querySelector('#chart-status-pegawai'), {
    chart: Object.assign({ type: 'donut', height: 290 }, commonChart),
    colors: ['#178957', '#286fc7', '#f39c12', '#7c3aed', '#94a3b8'],
    labels: <?=json_encode($statusPegawai['labels'] ?: ['Belum ada data']);?>,
    legend: { position: 'bottom' },
    plotOptions: { pie: { donut: { size: '68%' } } },
    series: emptyAware(<?=json_encode($statusPegawai['values']);?>)
  }).render();

  new ApexCharts(document.querySelector('#chart-jabatan-fungsional'), {
    chart: Object.assign({ type: 'donut', height: 320 }, commonChart),
    colors: ['#178957', '#1f9d6a', '#286fc7', '#f39c12', '#7c3aed', '#94a3b8'],
    labels: <?=json_encode($jabatan['labels'] ?: ['Belum ada data']);?>,
    legend: { position: 'bottom' },
    plotOptions: { pie: { donut: { size: '64%' } } },
    series: emptyAware(<?=json_encode($jabatan['values']);?>)
  }).render();

  new ApexCharts(document.querySelector('#chart-unit-kerja'), {
    chart: Object.assign({ type: 'bar', height: 320 }, commonChart),
    colors: ['#178957'],
    dataLabels: { enabled: false },
    grid: { borderColor: '#edf1f6' },
    plotOptions: { bar: { borderRadius: 4, horizontal: true } },
    series: [{ name: 'Dosen', data: emptyAware(<?=json_encode($unitValues);?>) }],
    xaxis: { categories: <?=json_encode($unitLabels ?: ['Belum ada data']);?>, min: 0 }
  }).render();
})();
</script>
