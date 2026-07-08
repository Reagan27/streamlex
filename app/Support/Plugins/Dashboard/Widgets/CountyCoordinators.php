<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CountyCoordinators extends Widget
{
    public ?string $width = '3';

    protected string|\Closure|array $permissions = 'subordinates.assignment';

    public function __construct(protected readonly UserRepository $users)
    {
    }

    public function render(): View
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasRole('Admin') && !$currentUser->hasRole('Manager') && !$currentUser->hasRole('Regional_Coordinator')) {
            return view('plugins.dashboard.widgets.empty');
        }

        $query = $this->users->query()
            ->whereHas('role', function($q) {
                $q->where('name', 'County_Coordinator');
            });
            // ->whereExists(function ($query) {
            //     $query->select(DB::raw(1))
            //           ->from('admin_contracts')
            //           ->whereColumn('admin_contracts.role_id', 'users.role_id')
            //           ->where('admin_contracts.status', 'published')
            //           ->where(DB::raw('DATE_ADD(admin_contracts.start_date, INTERVAL admin_contracts.number_of_days DAY)'), '>', Carbon::now());
            // });
        $currentUser = Auth::user();
$activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();

if ($activeProjectId) {
    $query->whereHas('projects', function ($q) use ($activeProjectId) {
        $q->where('projects.id', $activeProjectId);
    });
}
        if ($currentUser->hasRole('Regional_Coordinator')) {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereIn('county_id', $assignedCountyIds);
        }

        $count = $query->count();

        return view('plugins.dashboard.widgets.county-coordinators', [
            'count' => $count,
        ]);
    }
}