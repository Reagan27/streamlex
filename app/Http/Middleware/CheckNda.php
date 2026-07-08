<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckNda
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->nda_accepted) {
            
            $excluded = [
                'nda*',
                'onboarding*',
                'logout*',
                'auth*',
                'login*',
                'policy-acknowledgement*',
                'download/*',
            ];

            foreach ($excluded as $pattern) {
                if ($request->is($pattern)) {
                    return $next($request);
                }
            }

            return redirect()->route('nda.show');
        }

        return $next($request);
    }
}