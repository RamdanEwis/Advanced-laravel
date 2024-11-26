<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class AddJwtFromCookieToRequest
{
    /**
     * Handle incoming request 🛡️
     */
    public function handle($request, Closure $next)
    {
        // 🏷️ Check if JWT exists in the cookie
        if ($jwt = $request->cookie('access_token')) {
            // 📤 Add JWT to the Authorization header
            $request->headers->set('Authorization', "Bearer $jwt");
        }
        return $next($request);
    }
}
