# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project context

This is the Laravel API backend for the "better-erp" project. The sibling `../front` directory is a Next.js 16 + shadcn/ui admin dashboard that consumes this API. The backend is currently a near-stock Laravel 13 skeleton (PHP ^8.3) with Sanctum-based token authentication as the only implemented feature.

## Commands

Setup (fresh clone):
```bash
composer setup   # composer install, copy .env, key:generate, migrate, npm install, npm run build
```

Run the full local dev stack (server + queue listener + log tailing + Vite), all in one terminal via `concurrently`:
```bash
composer dev
```

Run pieces individually:
```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0   # live log viewer
npm run dev                    # Vite (Tailwind v4) asset watcher
```

Tests (PHPUnit, not Pest):
```bash
composer test                        # clears config cache, then runs php artisan test
php artisan test                     # run full suite
php artisan test --filter=testName   # run a single test by method name
php artisan test tests/Feature/ExampleTest.php   # run a single file
```
Test env is forced to sqlite in-memory, array cache/session, sync queue (see `phpunit.xml`) — no external services needed to run tests.

Code style:
```bash
vendor/bin/pint          # Laravel Pint, fixes in place
vendor/bin/pint --test   # check only, no changes
```

## Architecture

- **Routing**: `bootstrap/app.php` wires `routes/web.php`, `routes/api.php`, `routes/console.php`, and a health check at `/up`. No custom middleware groups are registered yet. API exceptions are forced to render as JSON whenever the request path is `api/*`.
- **Auth**: Token auth via Sanctum. `App\Models\User` uses `HasApiTokens`; `App\Providers\AppServiceProvider` swaps Sanctum's personal access token model for `App\Models\AccessToken` (a thin subclass of `Laravel\Sanctum\PersonalAccessToken`) — extend that model rather than Sanctum's default if adding token-related behavior. `Auth\LoginController::login` validates email/password/device_name, checks credentials manually, and returns a plain-text Sanctum token (there is no login route wired up in `routes/api.php` yet — only `GET /user` behind `auth:sanctum`).
- Only the `web` guard is defined in `config/auth.php`; there's no `api` guard, since Sanctum tokens are checked via the `auth:sanctum` middleware directly rather than a guard config.
- `config/cors.php` currently allows all origins/methods/headers with `supports_credentials: false` — fine for bearer-token auth from the Next.js frontend, but would need `supports_credentials: true` plus a locked-down origin list if switching to Sanctum's cookie-based SPA auth.
- Database migrations include the full Laravel Passport OAuth table set (`oauth_*` tables), but `laravel/passport` is not in `composer.json` and no Passport service provider is registered — these tables are currently unused; don't assume OAuth flows are active.
- Local DB is SQLite (`database/database.sqlite`); no MySQL/Postgres config is present.
