# API Documentation

This directory contains the OpenAPI 3.0 specification for the Yejara E-commerce API.

## Structure

```
docs/
└── api.yaml                    # Main aggregated API specification

modules/
├── Product/docs/api.yaml       # Product module API spec
├── Order/docs/api.yaml         # Order module API spec
├── Branch/docs/api.yaml        # Branch module API spec
└── User/docs/api.yaml          # User module API spec (future)
```

## Main API Specification

The main `api.yaml` file aggregates all module specifications into a single comprehensive API documentation. It includes:

- **Info**: API title, description, version, contact, license
- **Servers**: Development and production server URLs
- **Paths**: All API endpoints from all modules
- **Components**: Shared schemas and responses
- **Tags**: Organized by module (Products, Cart, Orders, Branches)

## Module API Specifications

Each module has its own `docs/api.yaml` file that documents:

- Module-specific endpoints
- Request/response schemas
- Examples
- Module-specific tags

### Product Module (`modules/Product/docs/api.yaml`)
- GET /api/products - List products
- POST /api/products - Create product

### Order Module (`modules/Order/docs/api.yaml`)
- GET /api/cart - Get cart
- POST /api/cart/items - Add to cart
- POST /api/orders/checkout - Checkout order

### Branch Module (`modules/Branch/docs/api.yaml`)
- GET /api/branches - List branches

## Viewing Documentation

### Swagger UI
Access the interactive API documentation at:
- Local: http://localhost:8080/api/documentation
- Docker: http://localhost/api/documentation

### Generate Documentation
```bash
# Generate Swagger docs from YAML
php artisan l5-swagger:generate

# Or with Docker
docker compose exec app php artisan l5-swagger:generate
```

## Editing Documentation

### YAML Format
The API documentation is written in YAML format following the OpenAPI 3.0 specification.

**Benefits:**
- ✅ Separation of concerns (docs separate from code)
- ✅ Easier to read and edit
- ✅ Better version control
- ✅ Non-developers can contribute
- ✅ Modular structure

### Best Practices

1. **Keep module specs focused**: Each module's `api.yaml` should only document that module's endpoints
2. **Use shared components**: Define common schemas in the main `api.yaml`
3. **Provide examples**: Include request/response examples for better understanding
4. **Document errors**: Include all possible error responses
5. **Keep in sync**: Update YAML when API changes

### Adding a New Endpoint

1. Add the endpoint to the appropriate module's `docs/api.yaml`
2. Add the endpoint to the main `docs/api.yaml`
3. Define any new schemas in `components/schemas`
4. Add examples for request/response
5. Run `php artisan l5-swagger:generate` to regenerate docs

### Adding a New Module

1. Create `modules/YourModule/docs/api.yaml`
2. Document all module endpoints
3. Add module endpoints to main `docs/api.yaml`
4. Add module tag to main spec
5. Regenerate documentation

## Configuration

L5-Swagger configuration is in `config/l5-swagger.php`:

```php
'format_to_use_for_docs' => 'yaml',  // Use YAML format
'annotations' => [
    base_path('docs'),               // Scan docs directory
],
'generate_yaml_copy' => true,        // Generate YAML copy
```

## Output Files

Generated documentation is stored in `storage/api-docs/`:
- `api-docs.json` - JSON format (for Swagger UI)
- `api-docs.yaml` - YAML format (backup copy)

## External Resources

- [OpenAPI 3.0 Specification](https://swagger.io/specification/)
- [Swagger Editor](https://editor.swagger.io/) - Online YAML editor
- [L5-Swagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)

## Migration from PHP Annotations

Previously, API documentation was defined using PHP annotations in controllers. We migrated to YAML for better maintainability.

**Old approach (PHP annotations):**
```php
#[OA\Get(path: '/api/products', ...)]
public function index() { ... }
```

**New approach (YAML):**
```yaml
paths:
  /api/products:
    get:
      summary: List products
      ...
```

The PHP annotations are still present in controllers but are no longer used for documentation generation.
