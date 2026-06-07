# Laravel Clean Auth Module

Module d authentification securise construit avec Laravel 12, PHP 8.3, PostgreSQL 16 et une Clean Architecture stricte en quatre couches.

## Stack

- PHP 8.3+ et Laravel 12
- PostgreSQL 16 en production
- Argon2id pour le hachage des mots de passe
- Sessions Laravel avec driver database
- Pest PHP 3 pour les tests unitaires et feature
- Blade et JavaScript vanilla, sans jQuery ni framework CSS
- Vite 6 disponible pour les assets JavaScript

## Architecture

```text
Presentation -> Application -> Domain <- Infrastructure
```

Le Domain ne depend ni de Laravel, ni d Eloquent, ni d un package externe applicatif. L Application depend du Domain. L Infrastructure implemente les ports du Domain. La Presentation orchestre les use cases.

## Securite incluse

- Argon2id avec 64 MB de memoire, 4 iterations et 1 thread
- Regeneration de session apres inscription et connexion
- Invalidation complete de session au logout
- Fingerprint IP + User-Agent sur les routes protegees
- Rate limiting a 5 requetes par minute sur POST /login et POST /register
- CSRF actif sur tous les formulaires POST
- Cookies HTTP-only, Secure et SameSite=Strict via `.env.example`
- Message de login volontairement vague pour limiter l enumeration
- Verification de hash meme quand l email est absent, puis delai de 200 ms sur erreur login
- Score de force mot de passe synchronise entre PHP et JavaScript

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Configurer PostgreSQL dans `.env` avant la migration :

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=clean_auth
DB_USERNAME=postgres
DB_PASSWORD=secret
```

## Routes

- `GET /register` : formulaire d inscription
- `POST /register` : inscription avec validation et rate limiting
- `GET /login` : formulaire de connexion
- `POST /login` : connexion avec session securisee
- `GET /dashboard` : page protegee
- `POST /logout` : deconnexion complete

## Tests

```bash
php artisan test
./vendor/bin/pint --test
./vendor/bin/phpstan analyse --level=8 app tests
```

## Production

- CI GitHub Actions: `.github/workflows/ci.yml` installe PHP 8.3, migre PostgreSQL 16, execute un smoke test auth PostgreSQL, puis lance tests, Pint et PHPStan.
- Deploiement HTTPS et secrets: `docs/deployment.md`.
- Backup et restore PostgreSQL: `docs/operations.md`, `scripts/backup-postgres.sh`, `scripts/restore-postgres.sh`.
- Exemple de variables production sans secret reel: `.env.production.example`.

Les tests feature utilisent SQLite en memoire via `phpunit.xml` pour rester rapides et reproductibles. La configuration de production reste PostgreSQL.

## Schema SQL

Le schema PostgreSQL de reference est disponible dans `database/schema.sql`.

```bash
psql -U postgres -d clean_auth < database/schema.sql
```

## Structure principale

```text
app/
├── Domain/Auth
├── Application/Auth
├── Infrastructure/Auth
└── Presentation/Http
```

## Checklist production

- Les 4 couches Clean Architecture sont presentes.
- Les ports Domain sont relies aux implementations Infrastructure dans `AuthDomainServiceProvider`.
- `AuthDomainServiceProvider` est enregistre dans `bootstrap/providers.php`.
- Les routes auth utilisent CSRF, throttle, session auth et fingerprint.
- Les migrations `users` et `sessions` sont presentes.
- Les tests unitaires couvrent Value Objects, service domaine et use cases.
- Les tests feature couvrent inscription, connexion, erreurs, dashboard protege et rate limit.
