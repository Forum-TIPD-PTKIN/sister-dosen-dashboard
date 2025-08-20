<?php
$api_key = 'AIzaSyD3fpYUnVbOvYy0xYFPQvJB0-Wo-HQxXLQ';
$cse_id = 'a0be1d7af74414737';
$author_name = 'Riem Malini Google Scholar';

// Search for the author using Google Custom Search API
$url = "https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cse_id&q=" . urlencode($author_name);
$response = file_get_contents($url);
$data = json_decode($response, true);

if (!empty($data['items'])) {
    $profile_url = $data['items'][0]['link'];
    echo "Profile URL: " . $profile_url . "\n";

    // Fetch the profile page and extract h-index
    $html_content = file_get_contents($profile_url);
    if ($html_content) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html_content);
        $xpath = new DOMXPath($dom);
        $hindex_node = $xpath->query('//*[@id="gsc_rsb_st"]/tbody/tr[2]/td[2]');
        if ($hindex_node->length > 0) {
            $hindex = $hindex_node->item(0)->textContent;
            echo "H-index (All): " . $hindex . "\n";
        } else {
            echo "H-index not found.\n";
        }
    } else {
        echo "Failed to fetch Google Scholar profile.\n";
    }
} else {
    echo "No results found.\n";
}
?>