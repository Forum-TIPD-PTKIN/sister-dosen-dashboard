<?php
include "backend/inc/config.php";
$api_key = 'AIzaSyD3fpYUnVbOvYy0xYFPQvJB0-Wo-HQxXLQ';
$cse_id = '75f0e3ac3ab3c479f';

function findScore($author_name) {
    global $api_key, $cse_id;
    $url = "https://customsearch.googleapis.com/customsearch/v1?key=$api_key&cx=$cse_id&q=" . urlencode($author_name);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    $result = [
        'sinta' => 'Not found',
        'google' => 'Not found',
        'scopus' => 'Not found'
    ];
    if (!empty($data['items'])) {
        $content = $data['items'][0]['snippet'] ?? '';
        if ($content) {
            preg_match('/Scopus H-Index\s*:\s*(\d+)/', $content, $scopus_match);
            $result['scopus'] = $scopus_match[1] ?? 'Not found';
            preg_match('/GS H-Index\s*:\s*(\d+)/', $content, $gs_match);
            $result['google'] = $gs_match[1] ?? 'Not found';
            preg_match_all('/(\d+)\. SINTA Score/', $content, $sinta_matches);
            $result['sinta'] = isset($sinta_matches[1][1]) ? $sinta_matches[1][1] : 'Not found';
        }
    }
    return $result;
}
?>
