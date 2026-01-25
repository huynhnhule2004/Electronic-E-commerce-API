<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Actions\CreateProductAction;
use Modules\Product\DTOs\CreateProductDto;
use Modules\Product\Resources\ProductResource;
use Modules\Product\Services\ProductCacheService;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: '/api/products',
        tags: ['Products'],
        summary: 'List products',
        description: 'Get cached list of products'
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(ref: '#/components/schemas/StandardResponse')
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated',
        content: new OA\JsonContent(ref: '#/components/schemas/StandardResponse')
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(ref: '#/components/schemas/StandardResponse')
    )]
    public function index(ProductCacheService $cache): JsonResponse
    {
        return $this->respond(true, ProductResource::collection($cache->list()), 'OK', 200);
    }

    #[OA\Post(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Create product',
        description: 'Create a product from DTO',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'sku', 'price'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Smartphone X'),
                    new OA\Property(property: 'sku', type: 'string', example: 'SPX-001'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 19999.0),
                    new OA\Property(
                        property: 'keywords',
                        type: 'array',
                        items: new OA\Items(type: 'string')
                    ),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new OA\JsonContent(ref: '#/components/schemas/StandardResponse')
    )]
    #[OA\Response(
        response: 401,
        description: 'Unauthenticated',
        content: new OA\JsonContent(ref: '#/components/schemas/StandardResponse')
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation error',
        content: new OA\JsonContent(ref: '#/components/schemas/StandardResponse')
    )]
    public function store(Request $request, CreateProductAction $action): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'sku' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'keywords' => ['array'],
            'keywords.*' => ['string'],
        ]);

        $dto = new CreateProductDto(
            $data['name'],
            $data['sku'],
            (float) $data['price'],
            $data['keywords'] ?? []
        );

        $product = $action($dto);

        return $this->respond(true, new ProductResource($product), 'Created', 200);
    }
}
