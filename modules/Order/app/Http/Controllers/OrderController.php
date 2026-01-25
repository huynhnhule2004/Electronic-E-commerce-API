<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Order\Actions\CheckoutOrderAction;
use Modules\Order\Actions\SendOrderConfirmationAction;
use Modules\Order\DTOs\CheckoutOrderDto;
use Modules\Order\DTOs\SendOrderConfirmationDto;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    #[OA\Post(
        path: '/api/orders/checkout',
        tags: ['Orders'],
        summary: 'Checkout order',
        description: 'Create an order from cart items',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['payment_method'],
                properties: [
                    new OA\Property(property: 'payment_method', type: 'string', example: 'cod'),
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
    public function checkout(
        Request $request,
        CheckoutOrderAction $action,
        SendOrderConfirmationAction $sendMail
    ): JsonResponse {
        $data = $request->validate([
            'payment_method' => ['required', 'string', 'in:cod,bank_transfer'],
        ]);

        $user = $request->user();

        if (! $user) {
            return $this->respond(false, null, 'Unauthenticated', 401);
        }

        if (empty(Session::get('cart', []))) {
            return $this->respond(false, null, 'Cart is empty', 422);
        }

        $dto = new CheckoutOrderDto(
            $user->id,
            $data['payment_method']
        );

        $order = $action($dto);

        $sendMail(new SendOrderConfirmationDto(
            $user->email,
            $user->name,
            $order->order_number,
            (float) $order->total_amount
        ));

        return $this->respond(true, $order, 'Created', 200);
    }
}
