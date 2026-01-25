# Electronic E-commerce API

Laravel 12 modular monolith for an electronic e-commerce platform.

## Quick Start (Docker)

1. Copy environment file:
	- Duplicate [.env.example](.env.example) to [.env](.env) and set DB + GEMINI credentials.
2. Build and run:
	- `docker compose up -d --build`
3. App setup (inside container):
	- `docker compose exec app php artisan migrate`
	- `docker compose exec app php artisan db:seed` (optional)
4. Queue worker:
	- `docker compose up -d worker`
	- `docker compose logs -f worker`

## API Docs (Swagger)

- Generate docs:
  - `docker compose exec app php artisan l5-swagger:generate`
- UI:
  - http://localhost/api/documentation

## Telescope

- Access: http://localhost/telescope
- Configure in [.env](.env): `TELESCOPE_ENABLED`, `TELESCOPE_QUERY_SLOW_MS`

## Tests

- Run tests:
  - `docker compose exec app php artisan test`

## Modules

Each module lives in `modules/<ModuleName>` with Laravel-like structure:

- app/Http/Controllers
- app/Models
- app/DTOs
- app/Actions
- app/Services
- app/Contracts
- app/Jobs
- app/Resources
- app/Providers
- database/migrations
- routes
- resources/views
- tests

Module docs:

- [modules/User/README.md](modules/User/README.md)
- [modules/Product/README.md](modules/Product/README.md)
- [modules/Order/README.md](modules/Order/README.md)
- [modules/Article/README.md](modules/Article/README.md)
- [modules/Branch/README.md](modules/Branch/README.md)
- [modules/Contact/README.md](modules/Contact/README.md)
