<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Actions\CreateCategoryAction;
use Modules\Product\Actions\UpdateCategoryAction;
use Modules\Product\DTOs\CategoryDto;
use Modules\Product\Models\Category;
use Modules\Product\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CreateCategoryAction $createAction,
        private readonly UpdateCategoryAction $updateAction,
    ) {}

    public function index(): JsonResponse
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
            'message' => 'OK',
            'code' => 200,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|max:2048', // 2MB
            'image' => 'nullable|image|max:5120', // 5MB
        ]);

        $dto = CategoryDto::fromRequest($request->all());
        $category = $this->createAction->execute($dto);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Category created successfully',
            'code' => 201,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|max:2048',
            'image' => 'nullable|image|max:5120',
        ]);

        $category = Category::findOrFail($id);
        $dto = CategoryDto::fromRequest($request->all());
        $category = $this->updateAction->execute($category, $dto);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
            'message' => 'Category updated successfully',
            'code' => 200,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Category deleted successfully',
            'code' => 200,
        ]);
    }
}
