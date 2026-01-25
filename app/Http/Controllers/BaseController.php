<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseController
{
    protected function respond(
        bool $success,
        mixed $data = null,
        string $message = '',
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ], $code);
    }
}
