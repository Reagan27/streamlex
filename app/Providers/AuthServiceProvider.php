<?php

namespace Vanguard\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Vanguard\User;
use Vanguard\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Vanguard\Model' => 'Vanguard\Policies\ModelPolicy',
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Blade directives for roles
        \Blade::directive('role', function ($expression) {
            return "<?php if (\\Auth::user()->hasRole({$expression})) : ?>";
        });

        \Blade::directive('endrole', function ($expression) {
            return '<?php endif; ?>';
        });

        // Blade directives for permissions
        \Blade::directive('permission', function ($expression) {
            return "<?php if (\\Auth::user()->hasPermission({$expression})) : ?>";
        });

        \Blade::directive('endpermission', function ($expression) {
            return '<?php endif; ?>';
        });

        // Gate for managing sessions
        \Gate::define('manage-session', function (User $user, $session) {
            if ($user->hasPermission('users.manage')) {
                return true;
            }

            return (int) $user->id === (int) $session->user_id;
        });

        // Gate for managing counties
        \Gate::define('manage-counties', function (User $user, User $targetUser) {
            // Admin and Manager can manage all counties
            if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
                return true;
            }

            // Regional Coordinator can only manage their own counties
            if ($user->id === $targetUser->id && $targetUser->role->name === 'Regional_Coordinator') {
                return true;
            }

            return false;
        });

        // Gate for updating user details
        \Gate::define('update-user', function (User $user, User $targetUser) {
            // Admin and Manager can update any user
            if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
                return true;
            }

            // Users can update their own details
            if ($user->id === $targetUser->id) {
                return true;
            }

            // Regional Coordinator can update their subordinates
            if ($user->role->name === 'Regional_Coordinator') {
                return $targetUser->county && $user->counties->contains($targetUser->county_id);
            }

            // County Coordinator can update their subordinates
            if ($user->role->name === 'County_Coordinator') {
                return $user->county_id === $targetUser->county_id &&
                    in_array($targetUser->role->name, ['Supervisor', 'Field_Officer']);
            }

            // Supervisor can update their field officers
            if ($user->role->name === 'Supervisor') {
                return $targetUser->role->name === 'Field_Officer' &&
                    $targetUser->supervisor_id === $user->id;
            }

            return false;
        });

        // Gate for viewing sensitive information
        \Gate::define('view-sensitive-info', function (User $user, User $targetUser) {
            return $user->hasRole('Admin') || $user->hasRole('Manager');
        });
    }
}