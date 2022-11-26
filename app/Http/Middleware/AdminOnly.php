<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Fail with HTTP 403 if the user is not role_id 1 (admin)
     *
     * Note that we override how this error is returned and displayed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @see App\Exceptions\Handler\render
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role->id != 1) {
            abort(403, 'adminonly');
        }
        return $next($request);
    }
}
