<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Electronic E-commerce API',
    version: '1.0.0',
    description: 'API documentation for Electronic E-commerce platform'
)]
#[OA\Schema(
    schema: 'StandardResponse',
    type: 'object',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: true),
        new OA\Property(property: 'data', type: 'object', nullable: true),
        new OA\Property(property: 'message', type: 'string', example: 'OK'),
        new OA\Property(property: 'code', type: 'integer', example: 200),
    ]
)]
final class OpenApi
{
}
