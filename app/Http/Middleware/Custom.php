<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class Custom
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->headers->get('Authorization');
        $token = str_replace('Basic ', '', $token);
        $token = base64_decode($token);
        if (env('TOKEN') !== $token) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
