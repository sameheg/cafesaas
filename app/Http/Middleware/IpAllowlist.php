<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IpAllowlist
{
    public function handle(Request $request, Closure $next)
    {
        $allowed = config('security.ip_allowlist', []);
        if ($allowed && ! in_array($request->ip(), $allowed, true)) {
            abort(403, 'IP not allowed');
        }

        return $next($request);
    }
}
