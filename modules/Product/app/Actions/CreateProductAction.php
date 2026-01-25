<?php

namespace Modules\Product\Actions;

use Modules\Product\Jobs\GenerateProductDescriptionJob;
use Modules\Product\DTOs\CreateProductDto;
use Modules\Product\Models\Product;

class CreateProductAction
{
    public function __invoke(CreateProductDto $dto): Product
    {
        $product = Product::query()->create([
            'name' => $dto->name,
            'slug' => $dto->sku,
            'keywords' => $dto->keywords,
        ]);

        GenerateProductDescriptionJob::dispatch($product->id, $dto->keywords);

        return $product;
    }
}
