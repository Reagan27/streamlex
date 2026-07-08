<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Supervisors extends Widget
{
    public ?string $width = '3';

    protected string|\Closure|array $permissions = 'subordinates.assignment';

    public function __construct(protected readonly UserRepository $users)
    {
    }
    public function render(): View
{
    $currentUser = Auth::user();
    $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();

    if ($currentUser->hasRole('Supervisor')) {
        return view('plugins.dashboard.widgets.empty');
    }

    $query = $this->users->query()
        ->whereHas('role', function($q) {
            $q->where('name', 'Supervisor');
        });

    // ADD PROJECT FILTER HERE ✅
    if ($activeProjectId) {
        $query->whereHas('projects', function ($q) use ($activeProjectId) {
            $q->where('projects.id', $activeProjectId);
        });
    }

    if ($currentUser->hasRole('Regional_Coordinator')) {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereIn('county_id', $assignedCountyIds);
        } elseif ($currentUser->hasRole('County_Coordinator')) {
            $query->where('county_id', $currentUser->county_id);
        }

        $count = $query->count();

        return view('plugins.dashboard.widgets.supervisors', [
            'count' => $count,
        ]);
    }
}