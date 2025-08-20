<?php
include "backend/inc/config.php";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://sister-api.kemdikbud.go.id/ws.php/1.0/referensi/profil_pt',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJiNzA3MWQwYy1kMzc5LTQ0OTMtYWM4Ni0xOGZjYzI1OWQ5MTMiLCJpc3MiOiJodHRwOlwvXC9zaXN0ZXItYXBpLmtlbWRpa2J1ZC5nby5pZFwvd3MucGhwXC8xLjBcL2F1dGhvcml6ZSIsImlhdCI6MTc1NDc1MjQwMywiZXhwIjoxNzU0NzU2MDAzLCJuYmYiOjE3NTQ3NTI0MDMsImp0aSI6ImI0MWM3YTMwYWNiMTIwODMifQ.DuzIgccjcdXLlXxc-orm8yxjTEgywiADCyECKxJfnuU'
  ),
));

$key = curl_exec($curl);
$datas = json_decode($key, true);
        $data = [
            'pt_id' => $datas['id_perguruan_tinggi'],
            'data_detail' => json_encode($datas, JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s')
        ];
        dump($data);
        $insert = $db->insert('pt_id', $data);
        echo $db->getErrorMessage();



if ($insert) {
    echo "Data successfully inserted.";
} else {
    echo "Error inserting data: " . $db->getErrorMessage();
}

curl_close($curl);
