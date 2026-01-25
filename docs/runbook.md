# Runbook

## Requirements
- Docker + Docker Compose
- (Optional) Local PHP 8.4

## Docker Commands
- Build: `docker compose build`
- Start: `docker compose up -d`
- Stop: `docker compose down`

## Database
- Migrate: `docker compose exec app php artisan migrate`
- Seed: `docker compose exec app php artisan db:seed` (optional)

## Queue
- Worker: `docker compose exec worker php artisan queue:work`

## Swagger
- Generate docs: `docker compose exec app php artisan l5-swagger:generate`
- UI: http://localhost/api/documentation

## Telescope
- UI: http://localhost/telescope
- Toggle: set `TELESCOPE_ENABLED` in .env

## Tests
- Run: `docker compose exec app php artisan test`
