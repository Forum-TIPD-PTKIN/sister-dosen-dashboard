<?php
include "backend/inc/config.php";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://sister-api.kemdikbud.go.id/ws.php/1.0/referensi/sdm',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJiNzA3MWQwYy1kMzc5LTQ0OTMtYWM4Ni0xOGZjYzI1OWQ5MTMiLCJpc3MiOiJodHRwOlwvXC9zaXN0ZXItYXBpLmtlbWRpa2J1ZC5nby5pZFwvd3MucGhwXC8xLjBcL2F1dGhvcml6ZSIsImlhdCI6MTc1NDc1NjExMCwiZXhwIjoxNzU0NzU5NzEwLCJuYmYiOjE3NTQ3NTYxMTAsImp0aSI6IjQ3MzNjNGJjM2I0MGYzMDYifQ.NCYKV31wmaVfFjDip3C6D_s9_4uFgBdFQoHMRXvb88o'
  ),
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
));

$response = curl_exec($curl);
if (curl_errno($curl)) {
    echo 'Curl error: ' . curl_error($curl);
    curl_close($curl);
    exit;
}
var_dump($response);

$decoded = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'JSON decode error: ' . json_last_error_msg();
    curl_close($curl);
    exit;
}

$data = [];
foreach ($decoded as $key) {
    $data[] = [
        'id_sdm' => $key['id_sdm'] ?? null,
        'nama_sdm' => rtrim($key['nama_sdm'] ?? null),
        'nidn' => rtrim($key['nidn'] ?? null),
        'nip' => rtrim($key['nip'] ?? null),
        'nuptk' => rtrim($key['nuptk'] ?? null),
        'nama_status_aktif' => rtrim($key['nama_status_aktif'] ?? null),
        'nama_status_pegawai' => rtrim($key['nama_status_pegawai'] ?? null),
        'jenis_sdm' => rtrim($key['jenis_sdm'] ?? null),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
}

if (!empty($data)) {
    dump($data);
    $insert = $db->insertMulti('tb_ref_sdm', $data);
    if ($insert) {
        echo "Data successfully inserted.";
    } else {
        echo "Error inserting data: " . $db->getErrorMessage();
    }
} else {
    echo "No data to insert.";
}

curl_close($curl);
