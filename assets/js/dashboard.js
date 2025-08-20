// Dashboard Main Module
class Dashboard {
    constructor() {
        this.data = {
            sdm: [],
            unitKerja: [],
            profilPT: null,
            referensi: {}
        };
        this.refreshInterval = null;
    }

    // Initialize dashboard
    async init() {
        try {
            showLoading(true);
            
            // Load referensi data first
            await this.loadReferensiData();
            
            // Load main data
            await this.loadDashboardData();
            
            // Update statistics
            this.updateStatistics();
            
            // Create charts
            await this.createCharts();
            
            // Load SDM table
            await this.loadSDMTable();
            
            // Setup auto-refresh
            this.setupAutoRefresh();
            
            showSuccess('Dashboard loaded successfully');
            
        } catch (error) {
            console.error('Dashboard initialization error:', error);
            showError('Failed to load dashboard: ' + error.message);
        } finally {
            showLoading(false);
        }
    }

    // Load all referensi data
    async loadReferensiData() {
        try {
            const referensiTypes = [
                'jabatan_fungsional',
                'jenjang_pendidikan',
                'agama',
                'status_kepegawaian'
            ];

            for (const type of referensiTypes) {
                try {
                    this.data.referensi[type] = await apiService.getReferensi(type);
                } catch (error) {
                    console.warn(`Failed to load referensi ${type}:`, error);
                    this.data.referensi[type] = [];
                }
            }

            // Load profil PT
            try {
                this.data.profilPT = await apiService.getProfilPT();
                if (this.data.profilPT && this.data.profilPT.id) {
                    this.data.unitKerja = await apiService.getUnitKerja(this.data.profilPT.id);
                }
            } catch (error) {
                console.warn('Failed to load profil PT:', error);
                this.data.unitKerja = [];
            }

        } catch (error) {
            console.error('Error loading referensi data:', error);
            throw error;
        }
    }

    // Load main dashboard data
    async loadDashboardData() {
        try {
            // Load SDM data with pagination
            this.data.sdm = await apiService.getSDM({ nama: '' });
            
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            this.data.sdm = [];
        }
    }

    // Update statistics cards
    updateStatistics() {
        const totalSDM = this.data.sdm.length;
        $('#totalSDM').text(totalSDM);
        
        // For now, set dummy data for other statistics
        // In real implementation, you would aggregate actual data
        $('#totalPenelitian').text(Math.floor(totalSDM * 0.3));
        $('#totalPublikasi').text(Math.floor(totalSDM * 0.5));
        $('#totalPengabdian').text(Math.floor(totalSDM * 0.4));
    }

    // Create all charts
    async createCharts() {
        try {
            // SDM by Unit Kerja Chart
            await this.createSDMUnitChart();
            
            // Jabatan Fungsional Chart
            await this.createJabatanChart();
            
            // Pendidikan Chart
            await this.createPendidikanChart();
            
            // Publikasi Trend Chart (with dummy data for now)
            await this.createPublikasiTrendChart();
            
        } catch (error) {
            console.error('Error creating charts:', error);
        }
    }

    // Create SDM by Unit Kerja chart
    async createSDMUnitChart() {
        try {
            if (this.data.sdm.length === 0 || this.data.unitKerja.length === 0) {
                chartManager.createEmptyChart('sdmUnitChart', 'No SDM data available');
                return;
            }

            const chartData = DataProcessor.processSDMByUnit(this.data.sdm, this.data.unitKerja);
            chartManager.createPieChart('sdmUnitChart', chartData, 'Distribusi SDM per Unit Kerja');
        } catch (error) {
            console.error('Error creating SDM unit chart:', error);
            chartManager.createEmptyChart('sdmUnitChart', 'Error loading data');
        }
    }

    // Create Jabatan Fungsional chart
    async createJabatanChart() {
        try {
            // For demo purposes, create dummy data based on referensi jabatan
            const jabatanRef = this.data.referensi.jabatan_fungsional || [];
            if (jabatanRef.length === 0) {
                chartManager.createEmptyChart('jabatanChart', 'No jabatan data available');
                return;
            }

            // Generate dummy distribution
            const dummyData = {
                labels: jabatanRef.slice(0, 5).map(j => j.nama || j.jenis || 'Unknown'),
                data: Array.from({length: 5}, () => Math.floor(Math.random() * 20) + 1)
            };

            chartManager.createBarChart('jabatanChart', dummyData, 'Distribusi Jabatan Fungsional');
        } catch (error) {
            console.error('Error creating jabatan chart:', error);
            chartManager.createEmptyChart('jabatanChart', 'Error loading data');
        }
    }

    // Create Pendidikan chart
    async createPendidikanChart() {
        try {
            const pendidikanRef = this.data.referensi.jenjang_pendidikan || [];
            if (pendidikanRef.length === 0) {
                chartManager.createEmptyChart('pendidikanChart', 'No education data available');
                return;
            }

            // Generate dummy distribution
            const dummyData = {
                labels: pendidikanRef.slice(0, 4).map(p => p.nama || p.jenis || 'Unknown'),
                data: Array.from({length: 4}, () => Math.floor(Math.random() * 30) + 5)
            };

            chartManager.createDoughnutChart('pendidikanChart', dummyData, 'Tingkat Pendidikan SDM');
        } catch (error) {
            console.error('Error creating pendidikan chart:', error);
            chartManager.createEmptyChart('pendidikanChart', 'Error loading data');
        }
    }

    // Create Publikasi Trend chart
    async createPublikasiTrendChart() {
        try {
            // Generate dummy trend data for last 5 years
            const currentYear = new Date().getFullYear();
            const years = Array.from({length: 5}, (_, i) => currentYear - 4 + i);
            
            const dummyData = {
                labels: years.map(y => y.toString()),
                data: Array.from({length: 5}, () => Math.floor(Math.random() * 50) + 10)
            };

            chartManager.createLineChart('publikasiTrendChart', dummyData, 'Trend Publikasi per Tahun');
        } catch (error) {
            console.error('Error creating publikasi trend chart:', error);
            chartManager.createEmptyChart('publikasiTrendChart', 'Error loading data');
        }
    }

    // Load SDM table
    async loadSDMTable() {
        try {
            const tableBody = $('#sdmTableBody');
            tableBody.empty();

            if (this.data.sdm.length === 0) {
                tableBody.append(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">No SDM data available</td>
                    </tr>
                `);
                return;
            }

            // Display first 10 records
            const displayData = this.data.sdm.slice(0, 10);
            
            displayData.forEach(sdm => {
                const row = `
                    <tr>
                        <td>${sdm.nama || 'N/A'}</td>
                        <td>${sdm.nidn || 'N/A'}</td>
                        <td>
                            <span class="badge bg-${this.getStatusBadgeColor(sdm.status_aktif)}">
                                ${sdm.status_aktif || 'Unknown'}
                            </span>
                        </td>
                        <td>${this.getUnitKerjaName(sdm.id_unit_kerja)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewSDMDetail('${sdm.id}')">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });

        } catch (error) {
            console.error('Error loading SDM table:', error);
            $('#sdmTableBody').html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">Error loading data</td>
                </tr>
            `);
        }
    }

    // Helper method to get unit kerja name
    getUnitKerjaName(unitId) {
        const unit = this.data.unitKerja.find(u => u.id === unitId);
        return unit ? unit.nama : 'Unknown Unit';
    }

    // Helper method to get status badge color
    getStatusBadgeColor(status) {
        switch (status?.toLowerCase()) {
            case 'aktif': return 'success';
            case 'non-aktif': return 'danger';
            case 'cuti': return 'warning';
            default: return 'secondary';
        }
    }

    // Setup auto-refresh
    setupAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }

        this.refreshInterval = setInterval(async () => {
            console.log('Auto-refreshing dashboard data...');
            try {
                await this.loadDashboardData();
                this.updateStatistics();
                await this.loadSDMTable();
                console.log('Dashboard auto-refresh completed');
            } catch (error) {
                console.error('Auto-refresh error:', error);
            }
        }, APP_SETTINGS.refreshInterval);
    }

    // Manual refresh
    async refresh() {
        await this.init();
    }

    // Cleanup
    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        chartManager.destroyAllCharts();
    }
}

// Global dashboard instance
let dashboard = null;

// Initialize dashboard when DOM is ready
async function initializeDashboard() {
    try {
        if (dashboard) {
            dashboard.destroy();
        }
        
        dashboard = new Dashboard();
        await dashboard.init();
        
    } catch (error) {
        console.error('Failed to initialize dashboard:', error);
        showError('Failed to initialize dashboard: ' + error.message);
    }
}

// Refresh SDM data function
async function refreshSDMData() {
    try {
        showLoading(true);
        await dashboard.loadDashboardData();
        dashboard.updateStatistics();
        await dashboard.loadSDMTable();
        showSuccess('SDM data refreshed successfully');
    } catch (error) {
        console.error('Error refreshing SDM data:', error);
        showError('Failed to refresh SDM data: ' + error.message);
    } finally {
        showLoading(false);
    }
}

// View SDM detail function
function viewSDMDetail(sdmId) {
    // For now, show an alert. In real implementation, this would open a modal or navigate to detail page
    alert(`View detail for SDM ID: ${sdmId}\n\nThis feature will show detailed information about the selected SDM.`);
}

// Utility functions
function showLoading(show) {
    const overlay = $('#loadingOverlay');
    if (show) {
        overlay.removeClass('d-none');
    } else {
        overlay.addClass('d-none');
    }
}

function showError(message) {
    const alert = $('#errorAlert');
    $('#errorMessage').text(message);
    alert.addClass('show');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        alert.removeClass('show');
    }, 5000);
}

function showSuccess(message) {
    // Create and show success toast
    const toast = $(`
        <div class="toast align-items-center text-white bg-success border-0 position-fixed" 
             style="top: 20px; right: 20px; z-index: 10000;" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    const toastEl = new bootstrap.Toast(toast[0]);
    toastEl.show();
    
    // Remove from DOM after hidden
    toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
