<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckOnboarding
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->isOnboardingComplete()) {
            \Log::info('CheckOnboarding middleware triggered for user.', ['user_id' => auth()->id()]);
            return redirect()->route('onboarding.navigate', 'welcome');
        }

        return $next($request);
    }
}