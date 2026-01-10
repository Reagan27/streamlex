<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogHttpRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle($request, Closure $next)
{
    logger()->info('HTTP REQUEST', [
        'method' => $request->method(),
        'url'    => $request->fullUrl(),
        'route'  => optional($request->route())->getName(),
        'action' => optional($request->route())->getActionName(),
    ]);

    return $next($request);
}

}
