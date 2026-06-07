# Operations Runbook

## Backup Policy

PostgreSQL is the source of truth for users and sessions. A production deployment must have scheduled backups before accepting real users.

Recommended baseline:

- Run `scripts/backup-postgres.sh` at least daily.
- Store backups outside the application host.
- Keep at least 7 daily and 4 weekly restore points.
- Run one restore drill before launch and after every backup tooling change.

## Backup

Set the database environment and run:

```bash
export DB_HOST=127.0.0.1
export DB_PORT=5432
export DB_DATABASE=clean_auth
export DB_USERNAME=clean_auth_app
export DB_PASSWORD='replace-with-secret'
export BACKUP_DIR=/var/backups/clean-auth

scripts/backup-postgres.sh
```

The script writes a custom-format PostgreSQL dump plus a SHA-256 checksum. The backup directory is created with private permissions.

## Restore Drill

Restore is intentionally guarded. The operator must set `RESTORE_CONFIRM` to the target database name:

```bash
export DB_HOST=127.0.0.1
export DB_PORT=5432
export DB_DATABASE=clean_auth_restore_test
export DB_USERNAME=clean_auth_app
export DB_PASSWORD='replace-with-secret'
export RESTORE_CONFIRM=clean_auth_restore_test

scripts/restore-postgres.sh /var/backups/clean-auth/clean_auth_20260607_180000.dump
```

Never test restores directly on production first. Restore into a separate database, run application smoke checks, then document the result.

## Local Production Smoke Test

After migration, verify the app can use PostgreSQL through Laravel, not only through `psql`:

```bash
php artisan migrate --force
php artisan migrate:status
php artisan test
```

A passing smoke test requires `pdo_pgsql` and `pgsql` in `php -m`.
