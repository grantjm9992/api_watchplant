<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Validation\UnauthorizedException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        $token = $request->headers->get('Authorization');
        $token = str_replace('Basic ', '', $token);
        $token = base64_decode($token);
        if (env('TOKEN') !== $token) {
            throw new UnauthorizedException();
        }
    }
}
