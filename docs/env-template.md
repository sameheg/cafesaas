# Environment Template

This template lists the essential environment variables required for new developers and tenants.
Copy `.env.example` to `.env` and adjust the values below for your setup.

## Base URL
- `APP_URL` – main application URL for the tenant.

## Database
- `DB_CONNECTION` – database driver, e.g. `mysql`.
- `DB_HOST` – database host.
- `DB_PORT` – database port, usually `3306`.
- `DB_DATABASE` – database name.
- `DB_USERNAME` – database username.
- `DB_PASSWORD` – database password.

## Redis
- `REDIS_HOST` – Redis host.
- `REDIS_PASSWORD` – password for Redis, if any.
- `REDIS_PORT` – Redis port, usually `6379`.

## Reverb
- `REVERB_APP_ID` – identifier from the Reverb dashboard.
- `REVERB_APP_KEY` – public key for Reverb.
- `REVERB_APP_SECRET` – secret key for Reverb.
- `REVERB_HOST` – Reverb server host.
- `REVERB_PORT` – Reverb server port, usually `6001`.
