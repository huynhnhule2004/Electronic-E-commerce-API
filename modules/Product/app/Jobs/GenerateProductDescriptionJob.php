<?php

namespace Modules\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Contracts\AIGenerationContract;
use Modules\Product\Models\Product;

class GenerateProductDescriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $productId,
        private array $keywords
    ) {
    }

    public function handle(AIGenerationContract $ai): void
    {
        $product = Product::query()->find($this->productId);

        if (!$product) {
            return;
        }

        $product->description = $ai->generateProductDescription($product->name, $this->keywords);
        $product->save();
    }
}
