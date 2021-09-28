<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class VerifyUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasCookie('uuid')) {
            return response()->json([
                'error' => 'User token is invalid!'
            ], 400);
        }

        $uuid = Cookie::get('uuid');

        // dd(\Crypt::decryptString($request->cookie('uuid')));

        // dd($next($request)->withCookie($uuid));

        return $next($request)->withCookie($uuid);
    }
}
