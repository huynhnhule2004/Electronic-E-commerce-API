# Product Module

## Purpose
Catalog management (products, categories, brands, variants) and AI-generated descriptions.

## Structure
- app/Http/Controllers: `ProductController`
- app/DTOs: `CreateProductDto`
- app/Actions: `CreateProductAction`
- app/Services: `GeminiService`, `ProductCacheService`
- app/Jobs: `GenerateProductDescriptionJob`
- app/Resources: `ProductResource`
- database/migrations: categories, brands, products, variants
- routes/api.php
- app/Providers: `ProductServiceProvider`

## API
- GET /api/products
- POST /api/products

## Notes
- AI prompt engineering in `GeminiService` with retry logic.
- Product list cached; invalidated via observer.
