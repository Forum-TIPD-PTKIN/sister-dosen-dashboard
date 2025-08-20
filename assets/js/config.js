// API Configuration
const API_CONFIG = {
    baseURL: 'https://sister-api.kemdikbud.go.id/ws.php/1.0', // Production endpoint
    sandboxURL: 'https://sister-api.kemdikbud.go.id/ws-sandbox.php/1.0', // Sandbox endpoint
    credentials: {
        id_pengguna: 'b7071d0c-d379-4493-ac86-18fcc259d913',
        username: 'fSRHczHyxxchUAbJo+mkAzvwDukm7G4QU4j/GOklciUQ0fbjQ12kxStoWPk12zZlos+eurmw3vzJm7DtNlu5Cnm8aqeo5gWgHmAtGgP+eKQ=',
        password: 'm5LPpdOlrUntWsqIFz29SmZFYYhLYGZ4qAsfb+PGyl5egM8KlROf8uDC1HdfDWDf20CzADHBXb1SvC+nKqynsR7wSuDIC4ALSKUM4irVWL0Z/ZxbuLkCd1LFr+ECkRYp',
        role: 'Sister-WS Basic'
    }
};

// Chart.js default configuration
Chart.defaults.global = Chart.defaults.global || {};
Chart.defaults.global.defaultFontFamily = 'Nunito, -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Helvetica Neue", Arial, sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Color palette for charts
const CHART_COLORS = {
    primary: '#4e73df',
    success: '#1cc88a',
    info: '#36b9cc',
    warning: '#f6c23e',
    danger: '#e74a3b',
    secondary: '#858796',
    light: '#f8f9fc',
    dark: '#5a5c69'
};

// Chart color schemes
const COLOR_SCHEMES = {
    default: [
        CHART_COLORS.primary,
        CHART_COLORS.success,
        CHART_COLORS.info,
        CHART_COLORS.warning,
        CHART_COLORS.danger,
        CHART_COLORS.secondary
    ],
    gradient: [
        '#667eea',
        '#764ba2',
        '#f093fb',
        '#f5576c',
        '#4facfe',
        '#00f2fe'
    ]
};

// Application settings
const APP_SETTINGS = {
    refreshInterval: 300000, // 5 minutes
    maxRetries: 3,
    requestTimeout: 30000 // 30 seconds
};
