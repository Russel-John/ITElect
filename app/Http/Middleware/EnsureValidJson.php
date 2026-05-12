<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidJson
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isJson() && filled($request->getContent())) {
            json_decode($request->getContent());

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'message' => 'Invalid JSON request body.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return $next($request);
    }
}
