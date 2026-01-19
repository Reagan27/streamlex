<?php

namespace Vanguard\Http\Middleware;

use Closure;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isBanned()) {
            abort(403, __('The service you are trying to access is experiencing an error. Please try again later.'));
        }

        return $next($request);
    }
}
