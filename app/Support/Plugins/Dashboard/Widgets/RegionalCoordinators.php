<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegionalCoordinators extends Widget
{
    public ?string $width = '3';

    protected string|\Closure|array $permissions = 'subordinates.assignment';

    public function __construct(protected readonly UserRepository $users)
    {
    }

    public function render(): View
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasRole('Admin') && !$currentUser->hasRole('Manager')) {
            return view('plugins.dashboard.widgets.empty');
        }
        $currentUser = Auth::user();
$activeProjectId = session('active_project_id') ?? $currentUser->getActiveProjectId();

$query = $this->users->query()
    ->whereHas('role', function($q) {
        $q->where('name', 'Regional_Coordinator');
    });

// ADD PROJECT FILTER HERE ✅
if ($activeProjectId) {
    $query->whereHas('projects', function ($q) use ($activeProjectId) {
        $q->where('projects.id', $activeProjectId);
    });
}

$count = $query->count();

        return view('plugins.dashboard.widgets.regional-coordinators', [
            'count' => $count,
        ]);
    }
}