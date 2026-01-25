<?php

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Electronic E-commerce API",
 *     version="1.0.0",
 *     description="API documentation for Electronic E-commerce platform"
 * )
 *
 * @OA\Get(
 *     path="/api/health",
 *     tags={"Health"},
 *     summary="Health check",
 *     @OA\Response(
 *         response=200,
 *         description="Service is healthy",
 *         @OA\JsonContent(ref="#/components/schemas/StandardResponse")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="StandardResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="data", type="object", nullable=true),
 *     @OA\Property(property="message", type="string", example="OK"),
 *     @OA\Property(property="code", type="integer", example=200)
 * )
 */
final class ApiDocs
{
}
