<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Order\Actions\AddToCartAction;
use Modules\Order\DTOs\AddToCartDto;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    #[OA\Post(
        path: '/api/cart/items',
        tags: ['Cart'],
        summary: 'Add item to cart',
        description: 'Add product to cart (session-based)',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id', 'quantity'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer', example: 1),
                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
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
    public function add(Request $request, AddToCartAction $action): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $dto = new AddToCartDto(
            (int) $data['product_id'],
            (int) $data['quantity']
        );

        $items = $action($dto);

        return $this->respond(true, $items, 'Added', 200);
    }

    #[OA\Get(
        path: '/api/cart',
        tags: ['Cart'],
        summary: 'Get cart',
        description: 'Get current cart items'
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
    public function index(): JsonResponse
    {
        return $this->respond(true, array_values(Session::get('cart', [])), 'OK', 200);
    }
}
