<?php
function findScore($author_name) {
    global $api_key, $cse_id;

    // Generate a unique cache file name based on the author name
    $cache_file = 'cache/search_' . md5($author_name) . '.json';
    $cache_duration = 604800; // 1 week in seconds

    // Check if cache exists and is not expired
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_duration) {
        $response = file_get_contents($cache_file);
        
    } else {
        // Make API request
        $url = "https://customsearch.googleapis.com/customsearch/v1?key=$api_key&cx=$cse_id&q=" . urlencode($author_name);
        $response = @file_get_contents($url);
        
        // Save to cache if request is successful
        if ($response !== false) {
            // Ensure cache directory exists
            if (!is_dir('cache')) {
                mkdir('cache', 0755, true);
            }
            file_put_contents($cache_file, $response);
        }
    }

    // Initialize default result
    $result = [
        'sinta' => 'Not found',
        'google' => 'Not found',
        'scopus' => 'Not found'
    ];

    // Process response if available
    if ($response !== false) {
        $data = json_decode($response, true);
        if (!empty($data['items'])) {
            $content = $data['items'][0]['snippet'] ?? '';
            if ($content) {
                preg_match('/Scopus H-Index\s*:\s*(\d+)/', $content, $scopus_match);
                $result['scopus'] = $scopus_match[1] ?? 'Not found';
                preg_match('/GS H-Index\s*:\s*(\d+)/', $content, $gs_match);
                $result['google'] = $gs_match[1] ?? 'Not found';
                preg_match_all('/(\d+)\. SINTA/', $content, $sinta_matches); 
                $result['sinta'] = isset($sinta_matches[1][1]) ? $sinta_matches[1][1] : 'Not found';
                // Ambil ID setelah "ID :"
                preg_match('/ID\s*:\s*(\d+)/', $content, $id_match);
                $result['id'] = $id_match[1] ?? 'Nothing';
            }
        }
    }

    return $result;
}
?>