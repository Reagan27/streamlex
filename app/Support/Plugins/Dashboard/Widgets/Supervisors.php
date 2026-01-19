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

        // If the current user is a Supervisor, they shouldn't see this widget
        if ($currentUser->hasRole('Supervisor')) {
            return view('plugins.dashboard.widgets.empty');
        }

        $query = $this->users->query()
            ->whereHas('role', function($q) {
                $q->where('name', 'Supervisor');
            });
            // ->whereExists(function ($query) {
            //     $query->select(DB::raw(1))
            //           ->from('admin_contracts')
            //           ->whereColumn('admin_contracts.role_id', 'users.role_id')
            //           ->where('admin_contracts.status', 'published')
            //           ->where(DB::raw('DATE_ADD(admin_contracts.start_date, INTERVAL admin_contracts.number_of_days DAY)'), '>', Carbon::now());
            // });

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