<?php

namespace Vanguard\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\PolicyAcknowledgement;

class CheckPolicyAcknowledgement
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $ack = PolicyAcknowledgement::where('user_id', $user->id)->first();
            if (!$ack && !$request->is('policy-acknowledgement*')) {
                return redirect()->route('policy.acknowledgement');
            }
        }
        return $next($request);
    }
}
