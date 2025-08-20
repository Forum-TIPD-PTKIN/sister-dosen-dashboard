<?php
require_once "inc/config.php";

$url_status = 'https://siakad.iainkerinci.ac.id/dashboard/check_report_briva.php';
$postData = [
    'awal' => '20250702',
    'akhir' => '20250702',
];

$ch = curl_init($url_status);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData)); // Encode POST data properly
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    die("Error: Failed to fetch data from API. HTTP Code: $httpCode");
}

$result = json_decode($response, true); // Decode as associative array
if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Invalid JSON response from API");
}


if (isset($result['status']) && $result['status'] == '1' && !empty($result['data'])) {
    $array_nomor = [];
    $array_nomor_peserta = [];
    $array_report_cust = [];
    $array_exist_nomor = [];

    // Collect data where amount is 250000.00
    foreach ($result['data'] as $data) {
        if ($data['amount'] == '250000.00') {
            $array_nomor[$data['custCode']] = $data;
            $array_nomor_peserta[] = "'" . $data['custCode'] . "'";
            $array_report_cust[] = $data['custCode'];
        }
    }

    // Check if there are any customer codes to query
    if (!empty($array_nomor_peserta)) {
        $implode_nomor = implode(",", $array_nomor_peserta);
        // Use prepared statement to prevent SQL injection
        $query = "SELECT custCode FROM report_briva WHERE custCode IN ($implode_nomor)";
        $check_report = $db->query($query);

        if ($check_report->rowCount() > 0) {
            foreach ($check_report as $exist) {
                $array_exist_nomor[] = $exist->custCode;
            }
        }

        // Find customer codes that are in $array_report_cust but not in $array_exist_nomor
        $array_new = array_diff($array_report_cust, $array_exist_nomor);

        if (!empty($array_new)) {
            foreach ($array_new as $nomor) {
            	 $insert_data[] = array(
            	'custCode' => $array_nomor[$nomor]['custCode'],
            	'nama' => $array_nomor[$nomor]['nama'],
            	'amount' => $array_nomor[$nomor]['amount'],
            	'paymentDate' => $array_nomor[$nomor]['paymentDate'],
            	'tellerid' => $array_nomor[$nomor]['tellerid'],
            );
            }
           $db->insertMulti('report_briva',$insert_data);
           echo count($insert_data)." data has been inserted";
        }
    } else {
        echo "No customer codes found with amount 250000.00";
    }
} else {
    echo "No valid data returned from API or status is not 1";
}