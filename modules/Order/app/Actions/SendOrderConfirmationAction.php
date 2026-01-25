<?php

namespace Modules\Order\Actions;

use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Modules\Order\DTOs\SendOrderConfirmationDto;

class SendOrderConfirmationAction
{
    public function __invoke(SendOrderConfirmationDto $dto): void
    {
        Mail::to($dto->email)->queue(new OrderConfirmationMail(
            $dto->orderNumber,
            $dto->customerName,
            $dto->totalAmount
        ));
    }
}
