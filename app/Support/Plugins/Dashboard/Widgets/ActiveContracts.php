<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Illuminate\Contracts\View\View;
use Vanguard\Plugins\Widget;
use Vanguard\Repositories\Contract\ContractRepository;
use Illuminate\Support\Facades\Auth;

class ActiveContracts extends Widget
{
    public ?string $width = '3';

    protected string|\Closure|array $permissions = 'contracts.manage';

    public function __construct(protected readonly ContractRepository $contracts)
    {
    }

    public function render(): View
    {
        $currentUser = Auth::user();
        $query = $this->contracts->query()->where('status', 'active');

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

        $activeContracts = $query->get();

        return view('plugins.dashboard.widgets.active-contracts', [
            'contracts' => $activeContracts,
        ]);
    }
}