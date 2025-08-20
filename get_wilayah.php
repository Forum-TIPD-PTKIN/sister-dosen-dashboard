<?php
include "backend/inc/config.php";
$curl = curl_init();


function curl($level) {
    global $curl;
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sister-api.kemdikbud.go.id/ws.php/1.0/referensi/wilayah?id_level_wilayah='.$level,
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
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

$level = array(
    0 => 'Negara',
    1 => 'Provinsi',
    2 => 'Kabupaten',
    3 => 'Kecamatan',
);
foreach ($level as $key => $value) {
    $response = curl($key);
    dump($response);
    $data = [];
    foreach (json_decode($response, true) as $item) {
        $data[] = [
            'id' => rtrim($item['id'], '"'),
            'nama' => $item['nama'],
            'id_induk_wilayah' => $item['id_induk_wilayah'],
            'id_level_wilayah' => $key,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    dump($data);
    $insert = $db->insertMulti('tb_ref_wilayah', $data);
}


if ($insert) {
    echo "Data successfully inserted.";
} else {
    echo "Error inserting data: " . $db->getErrorMessage();
}

curl_close($curl);
