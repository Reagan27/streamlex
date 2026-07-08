<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\Appraisal\AppraisalRepository;
use Illuminate\Support\Facades\Auth;

class TotalAppraisals extends Widget
{
    public ?string $width = '3';

    protected string|\Closure|array $permissions = 'appraisals.manage';

    public function __construct(protected readonly AppraisalRepository $appraisals)
    {
    } 
    public function render(): View
{
    $currentUser = Auth::user();
    $activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();
    
    $query = $this->appraisals->query();

    // ADD PROJECT FILTER HERE ✅
    if ($activeProjectId) {
        $query->whereHas('user.projects', function ($q) use ($activeProjectId) {
            $q->where('projects.id', $activeProjectId);
        });
    }

    if ($currentUser->hasRole('Regional_Coordinator')) {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereHas('user', function ($q) use ($assignedCountyIds) {
                $q->whereIn('county_id', $assignedCountyIds);
            });
        } elseif ($currentUser->hasRole('County_Coordinator')) {
            $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('county_id', $currentUser->county_id);
            });
        } elseif ($currentUser->hasRole('Supervisor')) {
            $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('supervisor_id', $currentUser->id);
            });
        }

        $count = $query->count();

        return view('plugins.dashboard.widgets.total-appraisals', [
            'count' => $count,
        ]);
    }
}