<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FieldOfficers extends Widget
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
    
    $query = $this->users->query()
        ->whereHas('role', function($q) {
            $q->where('name', 'Field_Officer');
        });

    if ($activeProjectId) {
        $query->whereHas('projects', function ($q) use ($activeProjectId) {
            $q->where('projects.id', $activeProjectId);
        });
    }

    if ($currentUser->hasRole('Regional_Coordinator')) {
        $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
        $query->whereIn('users.county_id', $assignedCountyIds);
    } elseif ($currentUser->hasRole('County_Coordinator')) {
            $query->where('users.county_id', $currentUser->county_id);
        } elseif ($currentUser->hasRole('Supervisor')) {
            $query->where('users.supervisor_id', $currentUser->id);
        }

        $count = $query->count();

        return view('plugins.dashboard.widgets.field-officers', [
            'count' => $count,
        ]);
    }
}