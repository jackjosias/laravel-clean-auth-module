# Production Deployment

This module is production-ready only when the Laravel app, PostgreSQL, HTTPS, and secrets are deployed together. The application expects secure sessions, so `SESSION_SECURE_COOKIE=true` requires real HTTPS at the public edge.

## Required Runtime

- PHP 8.3 with `pdo_pgsql` and `pgsql` enabled.
- Composer 2.
- PostgreSQL 16.
- A reverse proxy terminating TLS before PHP-FPM or the Laravel server.
- A secret manager or deployment system that injects `.env` values outside Git.

## Environment Secrets

Use `.env.production.example` as the contract. Never commit a real `.env`, `.env.production`, database password, or generated `APP_KEY`.

Generate the production key on the target host:

```bash
cp .env.production.example .env.production
php artisan key:generate --show
```

Paste the generated key into the production secret store, not into Git. Required secret values:

```text
APP_KEY
DB_PASSWORD
```

The production database user should be dedicated to this app and should not be the PostgreSQL superuser.

## PostgreSQL Bootstrap

Create a dedicated database and user before running migrations:

```sql
CREATE USER clean_auth_app WITH PASSWORD 'replace-with-strong-password';
CREATE DATABASE clean_auth OWNER clean_auth_app;
GRANT ALL PRIVILEGES ON DATABASE clean_auth TO clean_auth_app;
```

Then deploy the app with the injected environment and run:

```bash
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan migrate --force
php artisan config:cache
```

## HTTPS Reverse Proxy

### Caddy

```caddyfile
auth.example.com {
    encode zstd gzip
    reverse_proxy 127.0.0.1:8000
    header {
        X-Content-Type-Options nosniff
        X-Frame-Options DENY
        Referrer-Policy strict-origin-when-cross-origin
        Permissions-Policy "geolocation=(), microphone=(), camera=()"
    }
}
```

### Nginx

```nginx
server {
    listen 443 ssl http2;
    server_name auth.example.com;

    ssl_certificate /etc/letsencrypt/live/auth.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/auth.example.com/privkey.pem;

    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options DENY always;
    add_header Referrer-Policy strict-origin-when-cross-origin always;
    add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;
    }
}

server {
    listen 80;
    server_name auth.example.com;
    return 301 https://$host$request_uri;
}
```

## Release Gate

Before a release is accepted:

```bash
php -m | grep -E '^(pdo_pgsql|pgsql)$'
php artisan migrate:status
php artisan test
./vendor/bin/pint --test
./vendor/bin/phpstan analyse --level=8 app tests
```

The GitHub Actions workflow runs the same quality gate and also performs a PostgreSQL migration plus auth smoke test.
