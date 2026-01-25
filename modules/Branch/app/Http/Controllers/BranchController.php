<?php

namespace Modules\Branch\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Branch\Resources\BranchResource;
use Modules\Branch\Services\BranchCacheService;
use OpenApi\Attributes as OA;

class BranchController extends Controller
{
    #[OA\Get(
        path: '/api/branches',
        tags: ['Branches'],
        summary: 'List branches',
        description: 'Get cached list of branches'
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
    public function index(BranchCacheService $cache): JsonResponse
    {
        return $this->respond(true, BranchResource::collection($cache->list()), 'OK', 200);
    }
}
