#!/usr/bin/env bash
set -Eeuo pipefail

: "${DB_HOST:=127.0.0.1}"
: "${DB_PORT:=5432}"
: "${DB_DATABASE:?DB_DATABASE is required}"
: "${DB_USERNAME:?DB_USERNAME is required}"

backup_path="${1:-}"
if [[ -z "${backup_path}" || ! -f "${backup_path}" ]]; then
    printf 'Usage: DB_DATABASE=<target> RESTORE_CONFIRM=<target> %s <backup.dump>\n' "$0" >&2
    exit 2
fi

if [[ "${RESTORE_CONFIRM:-}" != "${DB_DATABASE}" ]]; then
    printf 'Refusing restore. Set RESTORE_CONFIRM=%s to confirm target database.\n' "${DB_DATABASE}" >&2
    exit 3
fi

if [[ -n "${DB_PASSWORD:-}" ]]; then
    export PGPASSWORD="${DB_PASSWORD}"
fi

pg_restore \
    --clean \
    --if-exists \
    --single-transaction \
    --no-owner \
    --no-acl \
    --host="${DB_HOST}" \
    --port="${DB_PORT}" \
    --username="${DB_USERNAME}" \
    --dbname="${DB_DATABASE}" \
    "${backup_path}"

printf 'Restore completed into database: %s\n' "${DB_DATABASE}"
