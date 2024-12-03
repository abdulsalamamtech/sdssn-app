<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Add roles you want to allow access to
        $accessible = ['admin', 'dev', 'super-admin'];
        if (!$request->user()
            || $request->user()->role != 'admin'
            || !in_array($request->user()->role, $accessible)
        ) {

            $response['success'] = false;
            $response['message'] = 'Unauthorized: Access Denied, You do not have the necessary permission(s)';
            $statusCode = 401;

            return response()->json($response, $statusCode);

        }

        return $next($request);

    }
}
