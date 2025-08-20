<?php

class SisterAPI {

    private $baseUrl;
    private $token;
    private $headers;
    
    public function __construct() {
        $this->baseUrl = SISTER_API_BASE_URL;
        $this->headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
    }
    
    /**
     * Authenticate and get token
     */
    public function authenticate() {
        $data = [
            'username' => SISTER_USERNAME,
            'password' => SISTER_PASSWORD,
            'id_pengguna' => SISTER_USER_ID
        ];
        
        $response = $this->makeRequest('/authorize', 'POST', $data, false);
        
        if ($response && isset($response['token'])) {
            $this->token = $response['token'];
            return $response['token'];
        }
        
        throw new Exception('Authentication failed');
    }    /**
     * Set token manually
     */
    public function setToken($token) {
        $this->token = $token;
    }
    
    /**
     * Make HTTP request to SISTER API
     */
    public function makeRequest($endpoint, $method = 'GET', $data = null, $requireAuth = true) {
        $url = $this->baseUrl . $endpoint;
        
        $headers = $this->headers;
        if ($requireAuth && $this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0, // No timeout limit like in Postman
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception('CURL Error: ' . $error);
        }
        
        if ($httpCode === 401) {
            throw new Exception('Authentication failed - Token expired or invalid');
        }
        
        if ($httpCode >= 400) {
            throw new Exception('HTTP Error ' . $httpCode . ': ' . $response);
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response: ' . json_last_error_msg());
        }
        
        return $decodedResponse;
    }
    
    /**
     * Get referensi data
     */
    public function getReferensi($type,$param=[]) {
        try {
            if(!empty($param)) {
                $queryString = http_build_query($param);
                $type .= '?' . $queryString;
            }
            return $this->makeRequest("/referensi/$type");
        } catch (Exception $e) {
            error_log("Error getting referensi $type: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get SDM data
     */
    public function getSDM($filters = []) {
        try {
            $endpoint = '/referensi/sdm';
            if (!empty($filters)) {
                $queryString = http_build_query($filters);
                $endpoint .= '?' . $queryString;
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting SDM data: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get unit kerja
     */
    public function getUnitKerja($idPerguruanTinggi = null) {
        try {
            if (!$idPerguruanTinggi) {
                // Try to get from profil PT first
                $profilPT = $this->getProfilPT();
                if ($profilPT && isset($profilPT['id'])) {
                    $idPerguruanTinggi = $profilPT['id'];
                } else {
                    return [];
                }
            }
            
            return $this->makeRequest("/referensi/unit_kerja?id_perguruan_tinggi=$idPerguruanTinggi");
        } catch (Exception $e) {
            error_log("Error getting unit kerja: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get profil perguruan tinggi
     */
    public function getProfilPT() {
        try {
            return $this->makeRequest('/referensi/profil_pt');
        } catch (Exception $e) {
            error_log("Error getting profil PT: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get perguruan tinggi list
     */
    public function getPerguruanTinggi() {
        try {
            return $this->makeRequest('/referensi/perguruan_tinggi');
        } catch (Exception $e) {
            error_log("Error getting perguruan tinggi: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get data pribadi
     */
    public function getDataPribadi($idSDM, $type = 'profil') {
        try {
            return $this->makeRequest("/data_pribadi/$type/$idSDM");
        } catch (Exception $e) {
            error_log("Error getting data pribadi: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get jabatan fungsional
     */
    public function getJabatanFungsional($idSDM = null) {
        try {
            $endpoint = '/jabatan_fungsional';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting jabatan fungsional: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get penelitian data
     */
    public function getPenelitian($idSDM = null) {
        try {
            $endpoint = '/penelitian';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting penelitian: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Foto data (returns binary image)
     */
    public function getFoto($idSDM = null) {
        try {
            $endpoint = '/data_pribadi/foto/';
            if ($idSDM) {
                $endpoint .= "$idSDM";
            }

            $url = $this->baseUrl . $endpoint;
          
            $headers = $this->headers;
            if ($this->token) {
                $headers[] = 'Authorization: Bearer ' . $this->token;
            }

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);
            $response = curl_exec($ch);

            //dump($response);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new Exception('CURL Error: ' . $error);
            }
            if ($httpCode === 401) {
                throw new Exception('Authentication failed - Token expired or invalid');
            }
            if ($httpCode >= 400) {
                throw new Exception('HTTP Error ' . $httpCode . ': ' . $response);
            }

            // Return raw binary image data
            return $response;
        } catch (Exception $e) {
            error_log("Error getting foto: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get publikasi data
     */
    public function getPublikasi($idSDM = null) {
        try {
            $endpoint = '/publikasi';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting publikasi: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get pengabdian data
     */
    public function getPengabdian($idSDM = null) {
        try {
            $endpoint = '/pengabdian';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting pengabdian: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get pendidikan formal
     */
    public function getPendidikanFormal($idSDM = null) {
        try {
            $endpoint = '/pendidikan_formal';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting pendidikan formal: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Penugasan
     */
    public function getPenugasan($idSDM = null) {
        try {
            $endpoint = '/penugasan';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting penugasan: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get semester data
     */
    public function getSemester() {
        try {
            return $this->makeRequest('/referensi/semester');
        } catch (Exception $e) {
            error_log("Error getting semester: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get kekayaan intelektual (HKI) data
     */
    public function getKekayaanIntelektual($idSDM = null) {
        try {
            $endpoint = '/kekayaan_intelektual';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting kekayaan intelektual: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check if response is successful
     */
    private function isSuccessResponse($response) {
        return $response !== null && !isset($response['error']);
    }
    
    /**
     * Get last error message
     */
    public function getLastError() {
        return $this->lastError ?? 'Unknown error';
    }

        /**
     * Get bidang ilmu for SDM
     */
    public function getBidangIlmu($idSDM) {
        try {
            return $this->makeRequest("/data_pribadi/bidang_ilmu/$idSDM");
        } catch (Exception $e) {
            error_log("Error getting bidang ilmu: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get alamat for SDM
     */
    public function getAlamat($idSDM) {
        try {
            return $this->makeRequest("/data_pribadi/alamat/$idSDM");
        } catch (Exception $e) {
            error_log("Error getting alamat: " . $e->getMessage());
            return [];
        }
    }
    //pengajaran
    public function getPengajaran($idSDM = null) {
        try {
            $endpoint = '/pengajaran';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting pengajaran: " . $e->getMessage());
            return [];
        }
    }
    //bimbingan_mahasiswa
    public function getBimbinganMahasiswa($idSDM = null) {
        try {
            $endpoint = '/bimbingan_mahasiswa';
            if ($idSDM) {
                $endpoint .= "?id_sdm=$idSDM";
            }
            return $this->makeRequest($endpoint);
        } catch (Exception $e) {
            error_log("Error getting bimbingan mahasiswa: " . $e->getMessage());
            return [];
        }
    }
}
?>
