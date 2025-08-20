<?php
require_once 'includes/config.php';
require_once 'includes/SisterAPI.php';

// Test the API connection and authentication
echo "<h2>SISTER API Connection Test</h2>";

try {
    $api = new SisterAPI();
    
    echo "<h3>1. Testing Authentication...</h3>";
    $token = $api->authenticate();
    
    if ($token) {
        echo "<div style='color: green;'>✅ Authentication successful!</div>";
        echo "<div>Token: " . substr($token, 0, 20) . "...</div>";
        
        echo "<h3>2. Testing API Calls...</h3>";
        
        // Test profil PT
        echo "<h4>Testing Profil PT:</h4>";
        $profilPT = $api->getProfilPT();
        if ($profilPT) {
            echo "<div style='color: green;'>✅ Profil PT retrieved successfully</div>";
            echo "<pre>" . json_encode($profilPT, JSON_PRETTY_PRINT) . "</pre>";
            
            // Test unit kerja
            if (isset($profilPT['id'])) {
                echo "<h4>Testing Unit Kerja:</h4>";
                $unitKerja = $api->getUnitKerja($profilPT['id']);
                if ($unitKerja) {
                    echo "<div style='color: green;'>✅ Unit Kerja retrieved successfully</div>";
                    echo "<div>Found " . count($unitKerja) . " unit kerja</div>";
                } else {
                    echo "<div style='color: orange;'>⚠️ No unit kerja found</div>";
                }
            }
        } else {
            echo "<div style='color: orange;'>⚠️ Profil PT not found</div>";
        }
        
        // Test SDM data
        echo "<h4>Testing SDM Data:</h4>";
        $sdmData = $api->getSDM();
        if ($sdmData) {
            echo "<div style='color: green;'>✅ SDM data retrieved successfully</div>";
            echo "<div>Found " . count($sdmData) . " SDM records</div>";
            if (count($sdmData) > 0) {
                echo "<div>Sample SDM:</div>";
                echo "<pre>" . json_encode($sdmData[0], JSON_PRETTY_PRINT) . "</pre>";
            }
        } else {
            echo "<div style='color: orange;'>⚠️ No SDM data found</div>";
        }
        
        // Test referensi data
        echo "<h4>Testing Referensi Data:</h4>";
        $agama = $api->getReferensi('agama');
        if ($agama) {
            echo "<div style='color: green;'>✅ Referensi agama retrieved successfully</div>";
            echo "<div>Found " . count($agama) . " agama records</div>";
        } else {
            echo "<div style='color: orange;'>⚠️ No referensi agama found</div>";
        }
        
    } else {
        echo "<div style='color: red;'>❌ Authentication failed - no token received</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<div><strong>Stack trace:</strong></div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<h3>Connection Information:</h3>";
echo "<div><strong>API Base URL:</strong> " . SISTER_API_BASE_URL . "</div>";
echo "<div><strong>User ID:</strong> " . SISTER_USER_ID . "</div>";
echo "<div><strong>Username:</strong> " . substr(SISTER_USERNAME, 0, 20) . "...</div>";
echo "<div><strong>PHP Version:</strong> " . phpversion() . "</div>";
echo "<div><strong>CURL Version:</strong> " . curl_version()['version'] . "</div>";
echo "<div><strong>SSL Version:</strong> " . curl_version()['ssl_version'] . "</div>";

// Test CURL extensions
if (function_exists('curl_init')) {
    echo "<div style='color: green;'>✅ CURL extension is available</div>";
} else {
    echo "<div style='color: red;'>❌ CURL extension is NOT available</div>";
}

if (function_exists('json_encode')) {
    echo "<div style='color: green;'>✅ JSON extension is available</div>";
} else {
    echo "<div style='color: red;'>❌ JSON extension is NOT available</div>";
}
?>
