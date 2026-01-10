<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // $onChangePasswordPage = $request->routeIs('password.force.change') || 
        //                         $request->is('change-password*');

        // 1. Expired initial password (>7 days)
        // if ($user->initial_password && $user->created_at->diffInDays(now()) > 7) {
        //     if (!$onChangePasswordPage && !$request->is('logout')) {
        //         Auth::logout();
        //         return redirect('/login')
        //             ->with('error', 'Your initial password has expired. Please contact your administrator to reset it.');
        //     }
        // }

        // 2. First login or still using initial password
        // if ($user->first_authentication || $user->initial_password) {
        //     if (!$onChangePasswordPage && !$request->is('logout')) {
        //         return redirect()->route('password.force.change');
        //     }
        // }

        return $next($request);
    }
}
