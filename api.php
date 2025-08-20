<?php
require_once 'includes/config.php';
require_once 'includes/SisterAPI.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
session_start();
if (!isset($_SESSION['sister_token']) || !isset($_SESSION['token_expiry'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Check if token is expired
if (time() > $_SESSION['token_expiry']) {
    http_response_code(401);
    echo json_encode(['error' => 'Token expired']);
    exit;
}

$api = new SisterAPI();
$api->setToken($_SESSION['sister_token']);

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'sdm':
            $filters = [];
            if (isset($_GET['nama'])) $filters['nama'] = $_GET['nama'];
            if (isset($_GET['nidn'])) $filters['nidn'] = $_GET['nidn'];
            if (isset($_GET['nip'])) $filters['nip'] = $_GET['nip'];
            
            $data = $api->getSDM($filters);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'unit_kerja':
            $idPT = $_GET['id_perguruan_tinggi'] ?? null;
            $data = $api->getUnitKerja($idPT);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'referensi':
            $type = $_GET['type'] ?? '';
            if (!$type) {
                throw new Exception('Type parameter required');
            }
            $data = $api->getReferensi($type);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'profil_pt':
            $data = $api->getProfilPT();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'jabatan_fungsional':
            $idSDM = $_GET['id_sdm'] ?? null;
            $data = $api->getJabatanFungsional($idSDM);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'penelitian':
            $idSDM = $_GET['id_sdm'] ?? null;
            $data = $api->getPenelitian($idSDM);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'publikasi':
            $idSDM = $_GET['id_sdm'] ?? null;
            $data = $api->getPublikasi($idSDM);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'pengabdian':
            $idSDM = $_GET['id_sdm'] ?? null;
            $data = $api->getPengabdian($idSDM);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'pendidikan_formal':
            $idSDM = $_GET['id_sdm'] ?? null;
            $data = $api->getPendidikanFormal($idSDM);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'data_pribadi':
            $idSDM = $_GET['id_sdm'] ?? '';
            $type = $_GET['data_type'] ?? 'profil';
            if (!$idSDM) {
                throw new Exception('id_sdm parameter required');
            }
            $data = $api->getDataPribadi($idSDM, $type);
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'semester':
            $data = $api->getSemester();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'dashboard_stats':
            // Get comprehensive dashboard statistics
            $stats = [];
            
            // Get SDM count
            $sdmData = $api->getSDM();
            $stats['total_sdm'] = count($sdmData);
            
            // For demonstration, generate some dummy statistics
            // In real implementation, you would fetch actual data
            $stats['total_penelitian'] = rand(50, 200);
            $stats['total_publikasi'] = rand(100, 500);
            $stats['total_pengabdian'] = rand(30, 150);
            
            // Process SDM by unit
            $unitKerja = $api->getUnitKerja();
            $sdmByUnit = [];
            if ($unitKerja && $sdmData) {
                $unitMap = [];
                foreach ($unitKerja as $unit) {
                    $unitMap[$unit['id']] = $unit['nama'];
                }
                
                foreach ($sdmData as $sdm) {
                    $unitName = $unitMap[$sdm['id_unit_kerja']] ?? 'Unknown';
                    $sdmByUnit[$unitName] = ($sdmByUnit[$unitName] ?? 0) + 1;
                }
            }
            
            $stats['sdm_by_unit'] = $sdmByUnit;
            
            echo json_encode(['success' => true, 'data' => $stats]);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
