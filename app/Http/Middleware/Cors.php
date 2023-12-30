<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
      // Define your allowed origins, methods, and headers
    $headers = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    ];

    // Check if it's a preflight (OPTIONS) request
    if ($request->isMethod('OPTIONS')) {
        return response('', 200)->withHeaders($headers);
    }

    // Pass the request to the next middleware
    $response = $next($request);

    // Add the CORS headers to the response
    foreach ($headers as $key => $value) {
        $response->header($key, $value);
    }
        return $response;
    }
}
