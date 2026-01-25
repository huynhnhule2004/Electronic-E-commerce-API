<?php

namespace Modules\Product\Contracts;

interface AIGenerationContract
{
    /**
     * @param array<string> $keywords
     */
    public function generateProductDescription(string $productName, array $keywords): string;
}
