# API Documentation Generation

## Quick Start

Generate API documentation from YAML files:

```bash
# Using Docker
docker compose exec app php artisan api:generate-docs

# Local
php artisan api:generate-docs
```

## What This Does

The command:
1. Reads `docs/api.yaml` (main specification)
2. Converts YAML to JSON format
3. Outputs to `storage/api-docs/api-docs.json`
4. Copies YAML to `storage/api-docs/api-docs.yaml`

## Viewing Documentation

After generation, access Swagger UI at:
- http://localhost/api/documentation (Docker - port 80)
- http://localhost:8000/api/documentation (local - php artisan serve)

## File Structure

```
docs/
├── api.yaml                          # Main API specification
└── README.md                         # Documentation guide

modules/
├── Product/docs/api.yaml             # Product module spec
├── Order/docs/api.yaml               # Order module spec
└── Branch/docs/api.yaml              # Branch module spec

storage/api-docs/
├── api-docs.json                     # Generated JSON (used by Swagger UI)
└── api-docs.yaml                     # Generated YAML (backup)
```

## Editing Documentation

1. Edit YAML files in `docs/` or `modules/*/docs/`
2. Run `php artisan api:generate-docs`
3. Refresh Swagger UI

## Module Specifications

Each module has its own API specification:

### Product Module
- **File**: `modules/Product/docs/api.yaml`
- **Endpoints**: 
  - GET /api/products
  - POST /api/products

### Order Module
- **File**: `modules/Order/docs/api.yaml`
- **Endpoints**:
  - GET /api/cart
  - POST /api/cart/items
  - POST /api/orders/checkout

### Branch Module
- **File**: `modules/Branch/docs/api.yaml`
- **Endpoints**:
  - GET /api/branches

## Main Specification

The main `docs/api.yaml` aggregates all module specs and includes:
- API info (title, version, description)
- Server configurations
- All endpoints from all modules
- Shared components (StandardResponse, error responses)
- Tags for organization

## Adding New Endpoints

1. Add to module's `docs/api.yaml`
2. Add to main `docs/api.yaml`
3. Define schemas if needed
4. Run `php artisan api:generate-docs`

## Troubleshooting

### Command not found
```bash
# Clear cache and regenerate autoload
composer dump-autoload
php artisan cache:clear
```

### YAML parse error
- Check YAML syntax at https://www.yamllint.com/
- Ensure proper indentation (2 spaces)
- Validate against OpenAPI 3.0 spec

### Swagger UI not showing changes
1. Clear browser cache (Ctrl+Shift+R)
2. Regenerate docs: `php artisan api:generate-docs`
3. Check `storage/api-docs/api-docs.json` was updated
4. Restart Docker if needed: `docker compose restart web`

## Resources

- [OpenAPI 3.0 Specification](https://swagger.io/specification/)
- [Swagger Editor](https://editor.swagger.io/)
- [YAML Validator](https://www.yamllint.com/)
