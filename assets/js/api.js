// API Service Module
class APIService {
    constructor() {
        this.baseURL = API_CONFIG.baseURL;
        this.retryCount = 0;
        this.maxRetries = APP_SETTINGS.maxRetries;
    }

    // Generic API call method
    async apiCall(endpoint, options = {}) {
        try {
            // Ensure token is valid
            await authManager.refreshTokenIfNeeded();
            
            const url = `${this.baseURL}${endpoint}`;
            const defaultOptions = {
                method: 'GET',
                headers: authManager.getAuthHeader(),
                timeout: APP_SETTINGS.requestTimeout
            };
            
            const finalOptions = { ...defaultOptions, ...options };
            
            console.log(`API Call: ${finalOptions.method} ${url}`);
            
            const response = await fetch(url, finalOptions);
            
            if (response.status === 401) {
                // Token expired, try to re-authenticate
                const reauth = await authManager.authenticate();
                if (reauth && this.retryCount < this.maxRetries) {
                    this.retryCount++;
                    return this.apiCall(endpoint, options);
                } else {
                    throw new Error('Authentication failed');
                }
            }
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            this.retryCount = 0; // Reset retry count on success
            return data;
            
        } catch (error) {
            console.error(`API Error for ${endpoint}:`, error);
            throw error;
        }
    }

    // Get referensi data
    async getReferensi(type) {
        return this.apiCall(`/referensi/${type}`);
    }

    // Get SDM data
    async getSDM(filters = {}) {
        let endpoint = '/referensi/sdm';
        const params = new URLSearchParams();
        
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                params.append(key, filters[key]);
            }
        });
        
        if (params.toString()) {
            endpoint += `?${params.toString()}`;
        }
        
        return this.apiCall(endpoint);
    }

    // Get unit kerja
    async getUnitKerja(idPerguruanTinggi) {
        return this.apiCall(`/referensi/unit_kerja?id_perguruan_tinggi=${idPerguruanTinggi}`);
    }

    // Get profil PT
    async getProfilPT() {
        return this.apiCall('/referensi/profil_pt');
    }

    // Get jabatan fungsional data
    async getJabatanFungsional(idSDM) {
        return this.apiCall(`/jabatan_fungsional?id_sdm=${idSDM}`);
    }

    // Get penelitian data
    async getPenelitian(idSDM) {
        return this.apiCall(`/penelitian?id_sdm=${idSDM}`);
    }

    // Get publikasi data  
    async getPublikasi(idSDM) {
        return this.apiCall(`/publikasi?id_sdm=${idSDM}`);
    }

    // Get pengabdian data
    async getPengabdian(idSDM) {
        return this.apiCall(`/pengabdian?id_sdm=${idSDM}`);
    }

    // Get pendidikan formal data
    async getPendidikanFormal(idSDM) {
        return this.apiCall(`/pendidikan_formal?id_sdm=${idSDM}`);
    }

    // Get data pribadi
    async getDataPribadi(idSDM, type = 'profil') {
        return this.apiCall(`/data_pribadi/${type}/${idSDM}`);
    }
}

// Global API service instance
const apiService = new APIService();

// Utility functions for data processing
const DataProcessor = {
    // Process SDM data for charts
    processSDMByUnit(sdmData, unitData) {
        const unitMap = {};
        unitData.forEach(unit => {
            unitMap[unit.id] = unit.nama;
        });

        const grouped = {};
        sdmData.forEach(sdm => {
            const unitName = unitMap[sdm.id_unit_kerja] || 'Unknown';
            grouped[unitName] = (grouped[unitName] || 0) + 1;
        });

        return {
            labels: Object.keys(grouped),
            data: Object.values(grouped)
        };
    },

    // Process jabatan fungsional data
    processJabatanFungsional(jabatanData, referensiJabatan) {
        const jabatanMap = {};
        referensiJabatan.forEach(jab => {
            jabatanMap[jab.id] = jab.nama;
        });

        const grouped = {};
        jabatanData.forEach(item => {
            const jabatanName = jabatanMap[item.id_jabatan_fungsional] || 'Unknown';
            grouped[jabatanName] = (grouped[jabatanName] || 0) + 1;
        });

        return {
            labels: Object.keys(grouped),
            data: Object.values(grouped)
        };
    },

    // Process pendidikan data
    processPendidikan(pendidikanData, referensiJenjang) {
        const jenjangMap = {};
        referensiJenjang.forEach(jen => {
            jenjangMap[jen.id] = jen.nama;
        });

        const grouped = {};
        pendidikanData.forEach(item => {
            const jenjangName = jenjangMap[item.id_jenjang_pendidikan] || 'Unknown';
            grouped[jenjangName] = (grouped[jenjangName] || 0) + 1;
        });

        return {
            labels: Object.keys(grouped),
            data: Object.values(grouped)
        };
    },

    // Process publikasi trend by year
    processPublikasiTrend(publikasiData) {
        const yearMap = {};
        
        publikasiData.forEach(pub => {
            const year = new Date(pub.tanggal_publikasi).getFullYear();
            yearMap[year] = (yearMap[year] || 0) + 1;
        });

        const sortedYears = Object.keys(yearMap).sort();
        
        return {
            labels: sortedYears,
            data: sortedYears.map(year => yearMap[year])
        };
    }
};
