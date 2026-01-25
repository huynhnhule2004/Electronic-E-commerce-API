<?php

namespace App\Http\Controllers;

/**
 * @OpenApi\Annotations\OpenApi(
 *     @OpenApi\Annotations\Info(
 *         title="Electronic E-commerce API",
 *         version="1.0.0",
 *         description="API documentation for Electronic E-commerce platform"
 *     )
 * )
 *
 * @OpenApi\Annotations\PathItem(
 *     path="/api/health",
 *     @OpenApi\Annotations\Get(
 *         tags={"Health"},
 *         summary="Health check",
 *         @OpenApi\Annotations\Response(
 *             response=200,
 *             description="Service is healthy",
 *             @OpenApi\Annotations\JsonContent(ref="#/components/schemas/StandardResponse")
 *         )
 *     )
 * )
 */
final class Swagger
{
}
