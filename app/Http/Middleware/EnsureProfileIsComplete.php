<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (empty($user->username) || empty($user->phone) || $user->profile_status !== 'completed')) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Profile is incomplete.',
                'code' => 403,
            ], 403);
        }

        return $next($request);
    }
}
