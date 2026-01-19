<?php

namespace Vanguard\Http\Middleware;

use Closure;

class CheckOnboarding
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->isOnboardingComplete()) {
            return redirect()->route('onboarding.navigate', 'welcome');
        }

        return $next($request);
    }
}