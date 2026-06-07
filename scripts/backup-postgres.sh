#!/usr/bin/env bash
set -Eeuo pipefail

: "${DB_HOST:=127.0.0.1}"
: "${DB_PORT:=5432}"
: "${DB_DATABASE:?DB_DATABASE is required}"
: "${DB_USERNAME:?DB_USERNAME is required}"
: "${BACKUP_DIR:=storage/app/backups/postgres}"

if [[ -n "${DB_PASSWORD:-}" ]]; then
    export PGPASSWORD="${DB_PASSWORD}"
fi

umask 077
mkdir -p "${BACKUP_DIR}"

timestamp="$(date -u +%Y%m%d_%H%M%S)"
backup_path="${BACKUP_DIR}/${DB_DATABASE}_${timestamp}.dump"
checksum_path="${backup_path}.sha256"

pg_dump \
    --format=custom \
    --no-owner \
    --no-acl \
    --host="${DB_HOST}" \
    --port="${DB_PORT}" \
    --username="${DB_USERNAME}" \
    --dbname="${DB_DATABASE}" \
    --file="${backup_path}"

sha256sum "${backup_path}" > "${checksum_path}"
printf 'Backup written: %s\nChecksum: %s\n' "${backup_path}" "${checksum_path}"
