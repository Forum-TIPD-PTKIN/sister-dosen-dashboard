#!/bin/bash
set -e

# Pastikan folder penyimpanan lokal ada dan memiliki permission yang benar
mkdir -p /var/www/html/cache \
         /var/www/html/upload/back_profil_foto \
         /var/www/html/upload/web_foto_upload \
         /var/www/html/upload/upload_excel
chown -R www-data:www-data /var/www/html/cache /var/www/html/upload
chmod -R 775 /var/www/html/cache /var/www/html/upload

# Tunggu database MariaDB siap
if [ -n "$DB_HOST" ]; then
    echo "Menunggu koneksi MariaDB di $DB_HOST..."
    until mariadb-admin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
        echo "MariaDB belum siap, menunggu..."
        sleep 2
    done
    echo "MariaDB siap!"

    # Inisialisasi skema database jika tabel belum ada
    TABLE_COUNT=$(mariadb -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -sse "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$DB_DATABASE';")
    if [ "$TABLE_COUNT" -eq 0 ]; then
        if [ -f "/var/www/html/db/sister_api.sql" ]; then
            echo "Mengimpor skema database awal dari sister_api.sql..."
            mariadb -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --default-character-set=utf8mb4 "$DB_DATABASE" < /var/www/html/db/sister_api.sql
            echo "Impor database selesai!"
        fi
    fi
fi

exec "$@"
