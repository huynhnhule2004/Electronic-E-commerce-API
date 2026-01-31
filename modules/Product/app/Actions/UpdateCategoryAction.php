<?php

namespace Modules\Product\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Product\DTOs\CategoryDto;
use Modules\Product\Models\Category;

class UpdateCategoryAction
{
    public function execute(Category $category, CategoryDto $dto): Category
    {
        $data = $dto->toArray();
        $data['slug'] = Str::slug($data['name']);

        if ($dto->icon) {
            // Delete old icon if exists
            if ($category->icon) {
                Storage::disk('public')->delete($category->icon);
            }
            $data['icon'] = $dto->icon->store('categories/icons', 'public');
        }

        if ($dto->image) {
             // Delete old image if exists
             if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $dto->image->store('categories/images', 'public');
        }

        $category->update($data);

        return $category;
    }
}
