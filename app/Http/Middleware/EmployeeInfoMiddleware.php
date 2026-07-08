<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Vanguard\Models\UserEducationCertificate;

class EmployeeInfoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow admins/managers to bypass
        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            return $next($request);
        }

        // Check Next of Kin / Emergency Contact
        $nokComplete = !empty($user->nok_full_name) && !empty($user->nok_mobile);

        // Check at least one Education/Professional Qualification document
        $hasEducationDoc = false;
        if (method_exists($user, 'educationDocuments') && $user->educationDocuments()->count() > 0) {
            $hasEducationDoc = true;
        }
        if (UserEducationCertificate::where('user_id', $user->id)->count() > 0) {
            $hasEducationDoc = true;
        }

        // Check Statutory & Compliance Declarations
        $statutoryComplete = $user->info_authorize;

        if (!($nokComplete && $hasEducationDoc && $statutoryComplete)) {
            // Only redirect if not already on employee info/profile page
            $routeName = Route::currentRouteName();
            if (!in_array($routeName, ['profile', 'profile.update.employeeinfo', 'users.edit'])) {
                return redirect()->route('profile')->with('modal_employeeinfo', true);
            }
        }

        return $next($request);
    }
}