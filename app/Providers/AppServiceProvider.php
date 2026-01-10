<?php

namespace Vanguard\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;           // ← Added
use Vanguard\Projects;                         // Already present
use Auth;                                      // Already present (or use auth() helper)

// Your repository bindings
use Vanguard\Repositories\Appraisal\AppraisalRepository;
use Vanguard\Repositories\Appraisal\EloquentAppraisalRepository;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\Country\EloquentCountry;
use Vanguard\Repositories\County\CountyRepository;
use Vanguard\Repositories\County\EloquentCounty;
use Vanguard\Repositories\Subcounty\SubcountyRepository;
use Vanguard\Repositories\Subcounty\EloquentSubcounty;
use Vanguard\Repositories\Ward\WardRepository;
use Vanguard\Repositories\Ward\EloquentWard;
use Vanguard\Repositories\Permission\EloquentPermission;
use Vanguard\Repositories\Permission\PermissionRepository;
use Vanguard\Repositories\Role\EloquentRole;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\Session\DbSession;
use Vanguard\Repositories\Session\SessionRepository;
use Vanguard\Repositories\User\EloquentUser;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Repositories\Asset\AssetRepository;
use Vanguard\Repositories\Asset\EloquentAsset;
use Vanguard\Repositories\Message\EloquentMessage;
use Vanguard\Repositories\Message\MessageRepository;
use Vanguard\Repositories\Email\EloquentEmail;
use Vanguard\Repositories\Email\EmailRepository;
use Vanguard\Repositories\Support\EloquentSupport;
use Vanguard\Repositories\Support\SupportRepository;
use Vanguard\Repositories\Support\IssuesCategoryRepository;
use Vanguard\Repositories\Visualization\VisualizationRepository;
use Vanguard\Repositories\Visualization\EloquentVisualization;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        config(['app.name' => setting('app_name')]);
        \Illuminate\Database\Schema\Builder::defaultStringLength(191);

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\Factories\\' . class_basename($modelName) . 'Factory';
        });

        \Illuminate\Pagination\Paginator::useBootstrap();

        // ================================================
        // Global Project Selector for Navbar
        // ================================================
 View::composer(['layouts.app', 'partials.navbar'], function ($view) {
    $user = auth()->user();

    if (!$user) {
        $projects = collect();
    } elseif (
        $user->isAdmin() ||
        $user->hasRole(['Manager', 'Finance'])
    ) {
        $projects = Projects::orderBy('name')->get();
    } elseif ($user->role->name === 'Regional_Coordinator') {
        $countyIds = $user->counties()->pluck('counties.id');
        $projects = Projects::whereHas('users', function ($q) use ($countyIds) {
            $q->whereIn('county_id', $countyIds);
        })->orderBy('name')->get();
    } elseif ($user->role->name === 'County_Coordinator') {
        $projects = Projects::whereHas('users', function ($q) use ($user) {
            $q->where('county_id', $user->county_id);
        })->orderBy('name')->get();
    } else {
        $projects = collect();
    }

    $view->with('projects', $projects);
});
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, EloquentUser::class);
        $this->app->singleton(RoleRepository::class, EloquentRole::class);
        $this->app->singleton(PermissionRepository::class, EloquentPermission::class);
        $this->app->singleton(SessionRepository::class, DbSession::class);
        $this->app->singleton(CountryRepository::class, EloquentCountry::class);
        $this->app->singleton(CountyRepository::class, EloquentCounty::class);
        $this->app->singleton(SubcountyRepository::class, EloquentSubcounty::class);
        $this->app->singleton(WardRepository::class, EloquentWard::class);
        $this->app->bind(AssetRepository::class, EloquentAsset::class);
        $this->app->bind(AppraisalRepository::class, EloquentAppraisalRepository::class);
        $this->app->bind(MessageRepository::class, EloquentMessage::class);
        $this->app->bind(EmailRepository::class, EloquentEmail::class);
        $this->app->bind(SupportRepository::class, EloquentSupport::class);
        $this->app->bind(IssuesCategoryRepository::class, EloquentIssuesCategory::class);
        $this->app->bind(VisualizationRepository::class, EloquentVisualization::class);
    }
}
