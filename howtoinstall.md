# Cara Install SISTER Dosen Dashboard

Panduan singkat ini memakai **Laragon di Windows**. Untuk Linux, nama folder dan permission dapat disesuaikan.

## 1. Siapkan Aplikasi Pendukung

Pastikan tersedia:

- PHP 8.1 atau lebih baru.
- MySQL 8/MariaDB.
- Apache atau Nginx.
- Composer 2.
- Ekstensi PHP: `curl`, `pdo_mysql`, `mbstring`, `openssl`, `simplexml`, `fileinfo`, `gd`, dan `zip`.

Pada Laragon, ekstensi dapat diaktifkan melalui **Menu > PHP > Extensions**, lalu restart Laragon.

Periksa PHP:

```powershell
php --version
php -m
```

## 2. Pasang Source dan Dependency

Letakkan proyek di:

```text
C:\laragon\www\sister-dosen-dashboard
```

Buka terminal pada root proyek, lalu buat konfigurasi lokal dari template:

```powershell
Copy-Item includes\config-example.php includes\config.php
Copy-Item backend\inc\config-example.php backend\inc\config.php
```

Pasang dependency Composer:

```powershell
cd backend\inc\lib
composer install
cd ..\..\..
```

File `includes/config.php` dan `backend/inc/config.php` diabaikan Git. Jangan memasukkan credential asli ke file `config-example.php`.

## 3. Siapkan Database

Cara termudah menggunakan HeidiSQL atau phpMyAdmin:

1. Buat database bernama `sister_api`.
2. Impor file `db/sister_api.sql`.
3. Buka `backend/inc/config.php`.
4. Sesuaikan koneksi database:

```php
$host = 'localhost';
$port = 3306;
$db_username = 'root';
$db_password = '';
$db_name = 'sister_api';
```

Untuk server production, gunakan pengguna database khusus dan password yang kuat.

## 4. Isi Credential SISTER

Credential Web Service diperoleh dari **Manajemen Akses SISTER (`man-akses`)** melalui Admin PT/pengelola SISTER perguruan tinggi.

Nilai yang diperlukan:

| Config | Sumber |
|---|---|
| `SISTER_USERNAME` | Username Web Service |
| `SISTER_PASSWORD` | Password Web Service |
| `SISTER_USER_ID` | UUID `id_pengguna` |
| `SISTER_ROLE` | Role dari respons `/authorize` |

Edit `includes/config.php`:

```php
define('SISTER_API_BASE_URL', 'https://sister-api.kemdiktisaintek.go.id/ws.php/1.0');
define('SISTER_USER_ID', 'UUID-PENGGUNA');
define('SISTER_USERNAME', 'USERNAME-WS');
define('SISTER_PASSWORD', 'PASSWORD-WS');
define('SISTER_ROLE', 'Sister-WS Consumer Basic');
```

Gunakan [dokumentasi `POST /authorize`](https://sister-api.kemdiktisaintek.go.id/ws.php/1.0#post-/authorize) untuk menguji credential.

> `id_pengguna` digunakan untuk login Web Service. `id_sdm` digunakan untuk mengambil data dosen dan tidak dimasukkan ke config. Endpoint [`data_pribadi/alamat/{id_sdm}`](https://sister-api.kemdiktisaintek.go.id/ws.php/1.0#get-/data_pribadi/alamat/-id_sdm-) bukan tempat mengambil credential.

## 5. Konfigurasi Penyimpanan Gambar

Aplikasi memakai dua jenis penyimpanan: folder lokal dan S3-compatible.

### A. Folder lokal

Folder lokal tetap diperlukan untuk foto pengguna, upload editor, file Excel, dan file sementara sebelum dikirim ke S3.

Jalankan dari root proyek:

```powershell
New-Item -ItemType Directory -Force cache
New-Item -ItemType Directory -Force upload\back_profil_foto
New-Item -ItemType Directory -Force upload\web_foto_upload
New-Item -ItemType Directory -Force upload\upload_excel
```

Pastikan Apache/PHP dapat menulis ke folder `cache` dan `upload`.

Pada Linux:

```bash
mkdir -p cache upload/back_profil_foto upload/web_foto_upload upload/upload_excel
chown -R www-data:www-data cache upload
chmod -R 775 cache upload
```

### B. Storage S3-compatible

Storage S3 dipakai oleh fitur foto profil dan upload gambar pada pengaturan aplikasi. Jika fitur tersebut tidak digunakan, konfigurasi S3 dapat dikosongkan.

Siapkan bucket terlebih dahulu, kemudian tambahkan konfigurasi ke tabel `s3_storage`:

```sql
INSERT INTO s3_storage
    (`type`, `url`, `bucket`, `key`, `secret`, `max_size`)
VALUES
    ('profil', 'https://s3.example.com', 'nama-bucket', 'ACCESS-KEY', 'SECRET-KEY', '5242880'),
    ('file', 'https://s3.example.com', 'nama-bucket', 'ACCESS-KEY', 'SECRET-KEY', '10485760');
```

Keterangan:

| Kolom | Isi |
|---|---|
| `type` | `profil` untuk gambar profil, `file` untuk file umum |
| `url` | Endpoint S3 tanpa `/` di bagian akhir |
| `bucket` | Nama bucket |
| `key` | Access key |
| `secret` | Secret key |
| `max_size` | Batas ukuran dalam byte; saat ini belum divalidasi otomatis oleh semua modul |

Bucket harus mengizinkan operasi `PutObject` dan `DeleteObject`. Implementasi saat ini mengunggah gambar dengan ACL `public-read`, sehingga provider harus mendukung ACL tersebut atau bucket harus memiliki aturan akses publik yang sesuai.

Jangan mengekspor isi tabel `s3_storage` ke repository karena access key dan secret disimpan di database.

## 6. Jalankan Aplikasi

Restart Apache dan MySQL, lalu buka:

| Halaman | URL |
|---|---|
| Dashboard | `http://sister-dosen-dashboard.test/` |
| Login SISTER | `http://sister-dosen-dashboard.test/login.php` |
| Backend admin | `http://sister-dosen-dashboard.test/backend/` |

Login backend bawaan dari dump database:

```text
Username: admin
Password: admin123
```

Segera ganti password setelah berhasil login.

## 7. Jika Terjadi Error

### `SQLSTATE[HY000] [1045] Access denied`

Username atau password MySQL pada `backend/inc/config.php` tidak sesuai. Cocokkan dengan akun MySQL yang aktif.

### `Call to undefined function curl_init()`

Aktifkan ekstensi `curl` pada PHP yang dipakai Apache, kemudian restart Laragon.

### SISTER mengembalikan `401 Unauthorized`

Periksa kembali username, password, UUID `id_pengguna`, role, dan URL production/sandbox.

### Upload gambar gagal

- Pastikan folder `upload` dapat ditulis PHP.
- Pastikan baris `type = profil` tersedia di tabel `s3_storage` jika memakai S3.
- Pastikan endpoint, bucket, access key, dan secret benar.
- Pastikan bucket mendukung `public-read` atau memiliki kebijakan akses yang sesuai.

## 8. Sebelum Dipublikasikan

- Ganti password admin default.
- Jangan commit `includes/config.php` atau `backend/inc/config.php`.
- Jangan commit credential SISTER atau storage.
- Gunakan HTTPS.
- Matikan `display_errors` pada production.
- Backup database sebelum sinkronisasi massal.

## Referensi

- [README aplikasi](README.md)
- [Dokumentasi SISTER Web Service](https://sister-api.kemdiktisaintek.go.id/ws.php/1.0)
- [Endpoint `POST /authorize`](https://sister-api.kemdiktisaintek.go.id/ws.php/1.0#post-/authorize)
- [OpenAPI SISTER](https://sister-api.kemdiktisaintek.go.id/wsv1.yaml)
