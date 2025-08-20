# SISTER Dashboard PHP

Dashboard grafik berbasis PHP untuk mengakses data dari SISTER Web Service API dengan tampilan modern menggunakan Bootstrap 5, Font Awesome, jQuery, dan Chart.js.

## 🚀 Fitur Utama

### 1. **Sistem Autentikasi PHP**
- ✅ Login form dengan validasi
- ✅ Session management dengan PHP
- ✅ Auto-refresh token JWT
- ✅ Secure logout dengan session cleanup

### 2. **Dashboard Responsif**
- ✅ Bootstrap 5 untuk responsive design
- ✅ Font Awesome 6 untuk icon-icon menarik
- ✅ jQuery 3.7 untuk interaktivitas
- ✅ Chart.js untuk visualisasi grafik

### 3. **Visualisasi Data Grafik**
- 📊 **Pie Chart**: Distribusi SDM per Unit Kerja
- 📈 **Bar Chart**: Distribusi Jabatan Fungsional  
- 🍩 **Doughnut Chart**: Tingkat Pendidikan SDM
- 📉 **Line Chart**: Trend Publikasi per Tahun

### 4. **API Backend PHP**
- RESTful API endpoints
- JSON response format
- Error handling yang robust
- Token validation

## 🗂️ Struktur File PHP

```
sisterapi/
├── index.php                  # Dashboard utama (PHP)
├── login.php                  # Halaman login
├── logout.php                 # Halaman logout
├── api.php                    # REST API endpoints
├── includes/
│   ├── config.php            # Konfigurasi sistem
│   └── SisterAPI.php         # Class untuk SISTER API
├── assets/
│   └── css/
│       └── dashboard.css     # Custom styling
└── README.md                 # Dokumentasi ini
```


## ⚙️ Konfigurasi

### Database (Opsional)
Edit `includes/config.php` untuk konfigurasi database jika diperlukan:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sister_dashboard');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### API Configuration
Kredensial API sudah dikonfigurasi dalam `includes/config.php`:

```php
define('SISTER_API_BASE_URL', 'https://sister-api.kemdikbud.go.id/ws.php/1.0');
define('SISTER_USER_ID', 'b7071d0c-d379-4493-ac86-18fcc259d913');
define('SISTER_USERNAME', 'fSRHczHyxxchUAbJo+mkAzvwDukm7G4QU4j/GOklciUQ0fbjQ12kxStoWPk12zZlos+eurmw3vzJm7DtNlu5Cnm8aqeo5gWgHmAtGgP+eKQ=');
define('SISTER_PASSWORD', 'm5LPpdOlrUntWsqIFz29SmZFYYhLYGZ4qAsfb+PGyl5egM8KlROf8uDC1HdfDWDf20CzADHBXb1SvC+nKqynsR7wSuDIC4ALSKUM4irVWL0Z/ZxbuLkCd1LFr+ECkRYp');
define('SISTER_ROLE', 'Sister-WS Basic');
```

## 🚀 Cara Instalasi

### 1. **Setup Web Server**
```bash
# Untuk Apache/Nginx dengan PHP 7.4+
# Pastikan PHP extension curl dan json aktif
```

### 2. **Deploy Files**
```bash
# Upload semua file ke web server
# Pastikan direktori includes/ dapat diakses oleh PHP
```

### 3. **Set Permissions**
```bash
chmod 755 *.php
chmod 755 includes/
chmod 644 includes/*
```

### 4. **Test Installation**
- Akses `login.php` di browser
- Klik "Masuk ke Dashboard" 
- Jika berhasil, akan redirect ke `index.php`

## 🎯 Flow Aplikasi

### 1. **Login Process**
```
login.php → SisterAPI::authenticate() → Set session → Redirect to index.php
```

### 2. **Dashboard Access**
```
index.php → Check session → Load data via SisterAPI → Display charts
```

### 3. **API Calls**
```
api.php → Validate session → Process request → Return JSON
```

### 4. **Logout Process**
```
logout.php → Destroy session → Show logout page → Redirect to login
```

## 📊 API Endpoints

### Internal API (`api.php`)
- `GET api.php?action=sdm` - Get SDM data
- `GET api.php?action=unit_kerja` - Get unit kerja
- `GET api.php?action=referensi&type=TYPE` - Get referensi data
- `GET api.php?action=profil_pt` - Get profil perguruan tinggi
- `GET api.php?action=dashboard_stats` - Get dashboard statistics

### SISTER API Endpoints yang Digunakan
- `/authorize` - Autentikasi
- `/referensi/*` - Data referensi
- `/referensi/sdm` - Data SDM
- `/referensi/unit_kerja` - Unit kerja
- `/referensi/profil_pt` - Profil perguruan tinggi

## 🔒 Keamanan

### 1. **Session Security**
- HTTP-only cookies
- Secure session configuration
- Session timeout (1 hour)
- CSRF protection ready

### 2. **Input Validation**
- XSS protection dengan `htmlspecialchars()`
- SQL injection prevention (jika menggunakan database)
- Input sanitization

### 3. **API Security**
- JWT token validation
- Request timeout protection
- Error handling yang aman

## 🛠️ Troubleshooting

### **Error: "Authentication failed"**
```php
// Check kredensial di includes/config.php
// Pastikan SISTER API dapat diakses
// Cek log error PHP untuk detail
```

### **Error: "Token expired"**
```php
// Token otomatis di-refresh
// Jika masih error, logout dan login kembali
// Check session timeout settings
```

### **Error: "CURL Error"**
```php
// Pastikan PHP curl extension aktif
// Check firewall/proxy settings
// Verify SSL certificates
```

### **Grafik tidak tampil**
```php
// Check browser console untuk error JavaScript
// Pastikan Chart.js library berhasil dimuat
// Verify JSON response dari API
```

## 📋 Requirements

### Server Requirements
- **PHP 7.4+** dengan extensions:
  - curl
  - json
  - session
- **Web Server**: Apache/Nginx
- **HTTPS** (recommended untuk production)

### Browser Support
- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

## 🔄 Development

### Adding New Features
1. **New API endpoint**: Add to `api.php`
2. **New chart**: Add to `index.php` JavaScript section
3. **New page**: Create new PHP file with session check

### Testing
```bash
# Test API endpoints
curl -X GET "http://localhost/sisterapi/api.php?action=sdm"

# Test authentication
curl -X POST "http://localhost/sisterapi/login.php"
```

## 📝 Logs & Monitoring

### Error Logging
```php
// PHP errors logged to system error log
// Custom API errors logged via error_log()
// Check server error logs for issues
```

### Performance Monitoring
- Monitor SISTER API response times
- Check session storage usage
- Monitor memory usage for large datasets

## 📞 Support

Untuk bantuan teknis:
1. Check error logs PHP
2. Verify SISTER API credentials
3. Test network connectivity
4. Review session configuration

---

## 📄 License

Sistem ini dibuat khusus untuk mengakses SISTER Web Service API sesuai dengan dokumentasi yang disediakan oleh Kemendikbudristek.

**© 2025 SISTER Dashboard PHP - Sistem Informasi SDM Diktiristek**
