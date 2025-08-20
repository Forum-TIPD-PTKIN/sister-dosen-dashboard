<?php
// Query: Jumlah Publikasi per Tahun
$publikasi_per_tahun = $db->query("SELECT YEAR(tanggal) as tahun, COUNT(*) as jumlah FROM tb_data_publikasi GROUP BY tahun ORDER BY tahun DESC LIMIT 8");

$publikasi_temp = array();
foreach ($publikasi_per_tahun as $row) {
    $publikasi_temp[] = array('tahun' => $row->tahun, 'jumlah' => (int)$row->jumlah);
}
usort($publikasi_temp, function($a, $b) { return $a['tahun'] <=> $b['tahun']; });
$publikasi_years = array_column($publikasi_temp, 'tahun');
$publikasi_counts = array_column($publikasi_temp, 'jumlah');

// Query: Jumlah Penelitian per Tahun
$penelitian_per_tahun = $db->query("SELECT tahun_pelaksanaan as tahun, COUNT(*) as jumlah FROM tb_data_penelitian GROUP BY tahun ORDER BY tahun DESC LIMIT 8");

$penelitian_temp = array();
foreach ($penelitian_per_tahun as $row) {
    $penelitian_temp[] = array('tahun' => $row->tahun, 'jumlah' => (int)$row->jumlah);
}
usort($penelitian_temp, function($a, $b) { return $a['tahun'] <=> $b['tahun']; });
$penelitian_years = array_column($penelitian_temp, 'tahun');
$penelitian_counts = array_column($penelitian_temp, 'jumlah');

// Query: Distribusi Jabatan Fungsional
$jabatan_data = $db->query("SELECT COALESCE(jafung, 'Lainnya') as jafung, COUNT(*) as jumlah FROM view_sdm_jabatan_fungsional GROUP BY jafung");
$jabatan_labels = array();
$jabatan_counts = array();
foreach ($jabatan_data as $row) {
    $jabatan_labels[] = $row->jafung;
    $jabatan_counts[] = (int)$row->jumlah;
}

// Query: Distribusi Status Pegawai
$status_pegawai_data = $db->query("SELECT COALESCE(nama_status_pegawai, 'Lainnya') as status_pegawai, COUNT(*) as jumlah FROM tb_ref_sdm GROUP BY nama_status_pegawai");
$status_pegawai_labels = array();
$status_pegawai_counts = array();
foreach ($status_pegawai_data as $row) {
    $status_pegawai_labels[] = $row->status_pegawai;
    $status_pegawai_counts[] = (int)$row->jumlah;
}
?>

<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-bar-chart"></i> Jumlah Publikasi per Tahun</h3>
      </div>
      <div class="box-body">
        <div id="chart-publikasi-tahun" style="height:350px;width:100%;"></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-flask"></i> Jumlah Penelitian per Tahun</h3>
      </div>
      <div class="box-body">
        <div id="chart-penelitian-tahun" style="height:350px;width:100%;"></div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-user-graduate"></i> Distribusi Jabatan Fungsional</h3>
      </div>
      <div class="box-body">
        <div id="chart-jabatan-fungsional" style="height:350px;width:100%;"></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-id-card"></i> Distribusi Status Pegawai</h3>
      </div>
      <div class="box-body">
        <div id="chart-status-pegawai" style="height:350px;width:100%;"></div>
      </div>
    </div>
  </div>
</div>

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
// Chart: Jumlah Publikasi per Tahun
Highcharts.chart('chart-publikasi-tahun', {
  chart: { type: 'column', height: 350 },
  title: { text: 'Jumlah Publikasi per Tahun' },
  xAxis: { categories: <?=json_encode($publikasi_years)?>, title: { text: 'Tahun' }, labels: { style: { fontSize: '16px', fontWeight: 'bold', color: '#181c24' } } },
  yAxis: { min: 0, title: { text: 'Jumlah Publikasi' }, labels: { style: { fontSize: '16px', fontWeight: 'bold', color: '#181c24' } } },
  legend: { enabled: true, align: 'center', verticalAlign: 'bottom' },
  plotOptions: {
    column: {
      dataLabels: { enabled: true, style: { fontWeight: 'bold', color: '#181c24', fontSize: '16px' }, format: '{y}' }
    },
    series: { marker: { enabled: true, radius: 5 } }
  },
  series: [
    { name: 'Publikasi', type: 'column', data: <?=json_encode($publikasi_counts)?>, color: '#43ea7a', zIndex: 1 },
    { name: 'Trend', type: 'spline', data: <?=json_encode($publikasi_counts)?>, color: '#1cc88a', zIndex: 2, marker: { enabled: true, radius: 6 }, dataLabels: { enabled: true, style: { fontWeight: 'bold', color: '#1cc88a', fontSize: '16px' }, format: '{y}' } }
  ]
});

// Chart: Jumlah Penelitian per Tahun
Highcharts.chart('chart-penelitian-tahun', {
  chart: { type: 'column', height: 350 },
  title: { text: 'Jumlah Penelitian per Tahun' },
  xAxis: { categories: <?=json_encode($penelitian_years)?>, title: { text: 'Tahun' }, labels: { style: { fontSize: '16px', fontWeight: 'bold', color: '#181c24' } } },
  yAxis: { min: 0, title: { text: 'Jumlah Penelitian' }, labels: { style: { fontSize: '16px', fontWeight: 'bold', color: '#181c24' } } },
  legend: { enabled: true, align: 'center', verticalAlign: 'bottom' },
  plotOptions: {
    column: {
      dataLabels: { enabled: true, style: { fontWeight: 'bold', color: '#181c24', fontSize: '16px' }, format: '{y}' }
    },
    series: { marker: { enabled: true, radius: 5 } }
  },
  series: [
    { name: 'Penelitian', type: 'column', data: <?=json_encode($penelitian_counts)?>, color: '#1cc88a', zIndex: 1 },
    { name: 'Trend', type: 'spline', data: <?=json_encode($penelitian_counts)?>, color: '#43ea7a', zIndex: 2, marker: { enabled: true, radius: 6 }, dataLabels: { enabled: true, style: { fontWeight: 'bold', color: '#43ea7a', fontSize: '16px' }, format: '{y}' } }
  ]
});

// Chart: Distribusi Jabatan Fungsional
Highcharts.chart('chart-jabatan-fungsional', {
  chart: { type: 'pie', height: 350 },
  title: { text: 'Distribusi Jabatan Fungsional' },
  tooltip: { pointFormat: '<b>{point.y}</b> orang ({point.percentage:.1f}%)' },
  plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, style: { fontSize: '16px', fontWeight: 'bold', color: '#181c24' }, format: '<b>{point.name}</b>: {point.percentage:.1f} %' } } },
  series: [{ name: 'Jumlah', colorByPoint: true, data: [
    <?php foreach($jabatan_labels as $i=>$label): ?>
      { name: <?=json_encode($label)?>, y: <?=json_encode($jabatan_counts[$i])?> },
    <?php endforeach; ?>
  ] }]
});

// Chart: Distribusi Status Pegawai
Highcharts.chart('chart-status-pegawai', {
  chart: { type: 'pie', height: 350 },
  title: { text: 'Distribusi Status Pegawai' },
  tooltip: { pointFormat: '<b>{point.y}</b> orang ({point.percentage:.1f}%)' },
  plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, style: { fontSize: '16px', fontWeight: 'bold', color: '#181c24' }, format: '<b>{point.name}</b>: {point.percentage:.1f} %' } } },
  series: [{ name: 'Jumlah', colorByPoint: true, data: [
    <?php foreach($status_pegawai_labels as $i=>$label): ?>
      { name: <?=json_encode($label)?>, y: <?=json_encode($status_pegawai_counts[$i])?> },
    <?php endforeach; ?>
  ] }]
});
</script>
