<?php

namespace Vanguard\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Vanguard\AdminContract;
use Vanguard\County;
use Vanguard\Projects;
use Vanguard\Role;
use Vanguard\User;
use Vanguard\UserContractSignature;

class ContractDashboardService
{
    public function getDashboardData(User $currentUser, array $filters = []): array
    {
        $contracts = $this->buildContractQuery($currentUser, $filters)
            ->with(['counties', 'userContractSignatures.user.role', 'userContractSignatures.user.county'])
            ->get();

        $contractIds = $contracts->pluck('id')->all();
        $signatures = UserContractSignature::whereIn('contract_id', $contractIds)
            ->with(['user.role', 'user.county', 'contract'])
            ->get();

        $today = Carbon::today();
        $publishedContracts = $contracts->filter(fn ($contract) => $contract->status === 'published')->count();
        $draftContracts = $contracts->filter(fn ($contract) => $contract->status === 'draft')->count();
        $droppedContracts = $contracts->filter(fn ($contract) => $contract->status === 'dropped')->count();
        $activeContracts = $contracts->filter(function ($contract) use ($today) {
            return $contract->status === 'published'
                && $contract->active_for_onboarding
                && (!$contract->end_date || $contract->end_date->gte($today));
        })->count();
        $expiredContracts = $contracts->filter(function ($contract) use ($today) {
            return $contract->end_date && $contract->end_date->lt($today);
        })->count();
        $expiringSoon = $contracts->filter(function ($contract) use ($today) {
            return $contract->end_date
                && $contract->end_date->between($today, $today->copy()->addDays(30));
        })->count();

        $pendingSignatures = $signatures->filter(fn ($signature) => in_array($signature->status, ['draft', 'pending'], true))->count();
        $signedSignatures = $signatures->filter(fn ($signature) => in_array($signature->status, ['approved', 'accepted'], true))->count();
        $declinedSignatures = $signatures->filter(fn ($signature) => $signature->status === 'declined')->count();
        $terminatedSignatures = $signatures->filter(fn ($signature) => $signature->status === 'terminated')->count();

        $statusChart = [
            'labels' => ['Draft', 'Published', 'Dropped', 'Inactive'],
            'values' => [
                $draftContracts,
                $publishedContracts,
                $droppedContracts,
                $contracts->filter(fn ($contract) => $contract->status === 'inactive')->count(),
            ],
        ];

        $signatureChart = [
            'labels' => ['Pending', 'Signed', 'Declined', 'Terminated'],
            'values' => [$pendingSignatures, $signedSignatures, $declinedSignatures, $terminatedSignatures],
        ];

        $monthlyTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthlyTrend->push([
                'label' => $month->format('M Y'),
                'value' => $contracts->filter(fn ($contract) => $contract->created_at && $contract->created_at->format('Y-m') === $monthKey)->count(),
            ]);
        }

        $countyBreakdown = $this->buildCountyBreakdown($contracts);
        $roleBreakdown = $this->buildRoleBreakdown($contracts);
        $projectBreakdown = $this->buildProjectBreakdown($contracts);
        
        $expiringContracts = $contracts->filter(function ($contract) use ($today) {
            return $contract->end_date && $contract->end_date->between($today, $today->copy()->addDays(30));
        })->sortBy('end_date')->values()->map(function ($contract) use ($today) {
            $daysRemaining = $today->diffInDays($contract->end_date, false);
            return [
                'title' => $contract->title,
                'end_date' => $contract->end_date ? $contract->end_date->format(config('app.date_format')) : 'N/A',
                'days_left' => $daysRemaining,
                'days_remaining' => max(0, $daysRemaining),
                'urgency' => $daysRemaining <= 7 ? 'critical' : ($daysRemaining <= 15 ? 'warning' : 'normal'),
            ];
        });

        $recentActivity = $this->buildRecentActivity($contracts, $signatures);
        $lifecycleSummary = [
            ['label' => 'Draft', 'value' => $draftContracts, 'color' => 'secondary'],
            ['label' => 'Published', 'value' => $publishedContracts, 'color' => 'primary'],
            ['label' => 'Signed', 'value' => $signedSignatures, 'color' => 'success'],
            ['label' => 'Expiring Soon', 'value' => $expiringSoon, 'color' => 'warning'],
            ['label' => 'Expired', 'value' => $expiredContracts, 'color' => 'danger'],
        ];

        $upcomingExpiry = $this->getUpcomingExpiryNotifications($contracts, $today);

        return [
            'contracts' => $contracts,
            'signatures' => $signatures,
            'summary' => [
                'total' => $contracts->count(),
                'active' => $activeContracts,
                'published' => $publishedContracts,
                'draft' => $draftContracts,
                'expiring_soon' => $expiringSoon,
                'expired' => $expiredContracts,
                'pending_signatures' => $pendingSignatures,
                'signed_signatures' => $signedSignatures,
                'declined_contracts' => $declinedSignatures,
                'terminated_contracts' => $terminatedSignatures,
            ],
            'statusChart' => $statusChart,
            'signatureChart' => $signatureChart,
            'monthlyTrend' => $monthlyTrend,
            'countyBreakdown' => $countyBreakdown,
            'roleBreakdown' => $roleBreakdown,
            'projectBreakdown' => $projectBreakdown,
            'expiringContracts' => $expiringContracts,
            'recentActivity' => $recentActivity,
            'lifecycleSummary' => $lifecycleSummary,
            'upcomingExpiry' => $upcomingExpiry,
            'filters' => $filters,
            'counties' => County::orderBy('name')->get(),
            'roles' => Role::orderBy('display_name')->get(),
            'projects' => Projects::orderBy('name')->get(),
        ];
    }

    protected function buildContractQuery(User $currentUser, array $filters = [])
    {
        $query = AdminContract::query();

        if (!$currentUser->hasRole(['Admin', 'Manager'])) {
            $roleHierarchyService = app(RoleHierarchyService::class);
            $subordinateRoles = $roleHierarchyService->getAllSubordinateRoles($currentUser->role?->name ?? '');

            if (!empty($subordinateRoles)) {
                $query->whereIn('role_id', function ($sub) use ($subordinateRoles) {
                    $sub->select('id')
                        ->from('roles')
                        ->whereIn('name', $subordinateRoles);
                });
            } else {
                $query->whereRaw('1 = 0');
            }

            if ($currentUser->role && $currentUser->role->name === 'Regional_Coordinator') {
                $assignedCountyIds = $currentUser->counties()->pluck('counties.id')->toArray();
                if (!empty($assignedCountyIds)) {
                    $query->whereHas('counties', function ($q) use ($assignedCountyIds) {
                        $q->whereIn('counties.id', $assignedCountyIds);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            } elseif (!empty($currentUser->county_id)) {
                $query->whereHas('counties', function ($q) use ($currentUser) {
                    $q->where('counties.id', $currentUser->county_id);
                });
            } else {
                $query->whereHas('userContractSignatures', function ($q) use ($currentUser) {
                    $q->where('user_id', $currentUser->id);
                });
            }
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('userContractSignatures.user', function ($uq) use ($search) {
                        $uq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['county'])) {
            $query->whereHas('counties', function ($q) use ($filters) {
                $q->where('counties.id', $filters['county']);
            });
        }

        if (!empty($filters['project'])) {
            $query->where('project_id', $filters['project']);
        }

        if (!empty($filters['role'])) {
            $query->where('role_id', $filters['role']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['signature_status'])) {
            $query->whereHas('userContractSignatures', function ($q) use ($filters) {
                $q->where('status', $filters['signature_status']);
            });
        }

        if (!empty($filters['date_range'])) {
            $range = $filters['date_range'];
            $start = null;
            $end = null;

            switch ($range) {
                case 'today':
                    $start = Carbon::today()->startOfDay();
                    $end = Carbon::today()->endOfDay();
                    break;
                case 'week':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    break;
                case 'month':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    break;
                case 'year':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    break;
            }

            if ($start && $end) {
                $query->whereBetween('start_date', [$start->toDateString(), $end->toDateString()]);
            }
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('start_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('start_date', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('created_at');
    }

    protected function buildCountyBreakdown(Collection $contracts): Collection
    {
        $breakdown = collect();

        foreach ($contracts as $contract) {
            foreach ($contract->counties as $county) {
                $name = $county->name ?? 'Unassigned';
                if (!$breakdown->has($name)) {
                    $breakdown->put($name, [
                        'id' => $county->id,
                        'name' => $name,
                        'total_contracts' => 0,
                        'signed_contracts' => 0,
                    ]);
                }

                $countyStats = $breakdown->get($name);
                $countyStats['total_contracts']++;

                // Count signed signatures for this contract-county combo
                $signedCount = $contract->userContractSignatures
                    ->filter(fn ($sig) => in_array($sig->status, ['approved', 'accepted']))
                    ->count();
                if ($signedCount > 0) {
                    $countyStats['signed_contracts']++;
                }

                $breakdown->put($name, $countyStats);
            }
        }

        return $breakdown->map(function ($county) {
            $county['compliance_percentage'] = $county['total_contracts'] > 0 
                ? round(($county['signed_contracts'] / $county['total_contracts']) * 100, 1)
                : 0;
            return $county;
        })->sortByDesc('total_contracts')->values();
    }

    protected function buildRoleBreakdown(Collection $contracts): Collection
    {
        return $contracts->groupBy(function ($contract) {
            return optional($contract->role)->display_name ?: 'Unassigned';
        })->map(function ($group, $name) {
            return [
                'name' => $name,
                'count' => $group->count(),
            ];
        })->sortByDesc('count')->values();
    }

    protected function buildProjectBreakdown(Collection $contracts): Collection
    {
        return $contracts->groupBy(function ($contract) {
            $project = $contract->project_id ? Projects::find($contract->project_id) : null;
            return $project ? $project->name : 'Unassigned';
        })->map(function ($group, $name) {
            return [
                'name' => $name,
                'count' => $group->count(),
            ];
        })->sortByDesc('count')->values();
    }

    protected function buildRecentActivity(Collection $contracts, Collection $signatures): Collection
    {
        $activity = collect();

        foreach ($contracts->sortByDesc('updated_at')->take(8) as $contract) {
            $activity->push([
                'title' => 'Contract updated: ' . ($contract->title ?: 'Untitled contract'),
                'timestamp' => $contract->updated_at,
                'badge' => ucfirst($contract->status),
            ]);
        }

        foreach ($signatures->sortByDesc('updated_at')->take(8) as $signature) {
            $activity->push([
                'title' => 'Signature updated for ' . optional($signature->user)->first_name . ' ' . optional($signature->user)->last_name,
                'timestamp' => $signature->updated_at,
                'badge' => ucfirst($signature->status),
            ]);
        }

        return $activity->sortByDesc('timestamp')->take(8)->values();
    }

    protected function getUpcomingExpiryNotifications(Collection $contracts, Carbon $today): array
    {
        $expiringToday = $contracts->filter(function ($contract) use ($today) {
            return $contract->end_date && $contract->end_date->isSameDay($today);
        })->count();

        $expiringThisWeek = $contracts->filter(function ($contract) use ($today) {
            return $contract->end_date 
                && $contract->end_date->isBetween($today, $today->copy()->addDays(6));
        })->count();

        $expiringThisMonth = $contracts->filter(function ($contract) use ($today) {
            return $contract->end_date
                && $contract->end_date->between($today, $today->copy()->endOfMonth());
        })->count();

        return [
            'expiring_today' => $expiringToday,
            'expiring_this_week' => $expiringThisWeek,
            'expiring_this_month' => $expiringThisMonth,
        ];
    }
}
