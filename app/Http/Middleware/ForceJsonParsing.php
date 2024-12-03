<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonParsing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        Log::info('Content-Type: ' . $request->header('Content-Type'));
        Log::info('All Input: ' . json_encode($request->all()));
        Log::info('Raw Input: ' . $request->getContent());
        $data = json_decode($request->getContent(), true);
        Log::info('Data: ' . json_encode($data));

        // Check if the method is one of the affected methods and the content type is JSON
        if (
            in_array($request->method(), ['POST', 'PUT', 'PATCH']) &&
            empty($request->all())
            // || $request->header('Content-Type') === 'application/json'
        ) {

            if (is_array($data)) {
                $request->merge($data);
            }
            // Decode JSON content and merge into the request
            $data = json_decode($request->getContent(), true);
            // dd($request->getContent(), $request->all(), $data, __LINE__);
        }else {
            // dd("else", $request->method(), $request->getContent(), $request->all(), $data, __LINE__);

        }
        return $next($request);
    }
}
