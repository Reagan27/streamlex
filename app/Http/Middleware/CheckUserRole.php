<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $allowedRoles = ['Admin', 'Regional Coordinator', 'County Coordinator', 'Supervisor', 'Field Officer'];

        if (!$user || !in_array($user->role->name, $allowedRoles)) {
            return redirect('/')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
