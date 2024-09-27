<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKey
{
    /**
     * Verify if the incoming request has a valid api key
     * NB: This is another security layer on top of sanctum to prevent access from
     * routes that don't require authentication.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->headers->has('x-api-key') || $request->header('x-api-key') !== config('app.api_key')) {
            abort(403, __('Unauthorized'));
        }
        return $next($request);
    }
}
