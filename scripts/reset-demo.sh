#!/usr/bin/env bash
set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="/home/apps/app-sisterdosen"
LOG_FILE="/var/log/sister-demo-reset.log"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] === Memulai reset demo SISTER Dashboard ===" >> "${LOG_FILE}"

# 1. Pastikan kontainer sister_db berjalan
if ! docker ps --format '{{.Names}}' | grep -q "^sister_db$"; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Menyalakan container sister_db..." >> "${LOG_FILE}"
    cd "${PROJECT_DIR}" && docker compose up -d sister_db
    sleep 5
fi

# 2. Restore database (menggunakan root agar view DEFINER berhasil dipulihkan)
if [ -f "${PROJECT_DIR}/demo-pristine.sql" ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Memulihkan database sister_api..." >> "${LOG_FILE}"
    docker exec -i sister_db mariadb -u root -psister_root_pass_2026 sister_api < "${PROJECT_DIR}/demo-pristine.sql"
fi

# 3. Restore folder upload bersih
if [ -f "${PROJECT_DIR}/demo-upload-pristine.tar.gz" ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Memulihkan folder upload..." >> "${LOG_FILE}"
    rm -rf "${PROJECT_DIR}/upload"/*
    tar -xzf "${PROJECT_DIR}/demo-upload-pristine.tar.gz" -C "${PROJECT_DIR}"
    chown -R 82:82 "${PROJECT_DIR}/upload" 2>/dev/null || true
    chmod -R 775 "${PROJECT_DIR}/upload" 2>/dev/null || true
fi

# 4. Bersihkan cache
rm -rf "${PROJECT_DIR}/cache"/* 2>/dev/null || true

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Reset demo SISTER Dashboard selesai." >> "${LOG_FILE}"
