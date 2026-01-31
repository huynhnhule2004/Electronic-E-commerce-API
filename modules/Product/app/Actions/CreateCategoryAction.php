<?php

namespace Modules\Product\Actions;

use Illuminate\Support\Str;
use Modules\Product\DTOs\CategoryDto;
use Modules\Product\Models\Category;

class CreateCategoryAction
{
    public function execute(CategoryDto $dto): Category
    {
        $data = $dto->toArray();
        $data['slug'] = Str::slug($data['name']);

        if ($dto->icon) {
            $data['icon'] = $dto->icon->store('categories/icons', 'public');
        }

        if ($dto->image) {
            $data['image'] = $dto->image->store('categories/images', 'public');
        }

        return Category::create($data);
    }
}
