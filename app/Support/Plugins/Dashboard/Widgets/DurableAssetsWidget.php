<?php

namespace Vanguard\Support\Plugins\Dashboard\Widgets;

use Vanguard\Plugins\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Vanguard\Services\RoleHierarchyService;
use Vanguard\AssetAssignment;
use Vanguard\UserInventory;
use Vanguard\User;

class DurableAssetsWidget extends Widget 
{
    public ?string $width = '8';

    protected string|\Closure|array $permissions = 'assets.assign';

    protected $roleHierarchyService;

    public function __construct(RoleHierarchyService $roleHierarchyService)
    {
        $this->roleHierarchyService = $roleHierarchyService;
    }

    public function render(): View
    {
        $currentUser = auth()->user();
        
        if ($currentUser->isAdmin() || $currentUser->hasRole('Manager')) {
            $durableAssets = $this->getAdminManagerView();
        } elseif ($currentUser->role->name === 'Regional_Coordinator') {
            $durableAssets = $this->getRegionalCoordinatorView($currentUser);
        } elseif ($currentUser->role->name === 'County_Coordinator') {
            $durableAssets = $this->getCountyCoordinatorView($currentUser);
        } elseif ($currentUser->role->name === 'Supervisor') {
            $durableAssets = $this->getSupervisorView($currentUser);
        } else {
            $durableAssets = $this->getUserInventoryView($currentUser);
        }

        return view('plugins.dashboard.widgets.durable-assets', [
            'durableAssets' => $durableAssets,
            'userRole' => $currentUser->role->name
        ]);
    }

    private function getAdminManagerView()
    {
        // Get all durable assets with total inventory and assignments
        return DB::table('assets')
            ->select(
                'assets.id',
                'assets.name',
                'assets.quantity as total_quantity',
                DB::raw('(
                    SELECT COUNT(*)
                    FROM asset_assignments
                    WHERE asset_assignments.asset_id = assets.id
                    AND asset_assignments.assignment_status = "Assigned"
                ) as assigned_count'),
                DB::raw('(
                    SELECT SUM(quantity)
                    FROM user_inventories
                    WHERE user_inventories.asset_id = assets.id
                ) as distributed_quantity')
            )
            ->where('assets.category', 'Durable')
            ->get()
            ->map(function($asset) {
                $asset->available_quantity = $asset->total_quantity - ($asset->assigned_count + ($asset->distributed_quantity ?? 0));
                return $asset;
            });
    }

    private function getRegionalCoordinatorView($user)
    {
        $assignedCounties = $user->counties()->pluck('counties.id');
        
        return DB::table('user_inventories')
            ->join('users', 'users.id', '=', 'user_inventories.user_id')
            ->join('assets', 'assets.id', '=', 'user_inventories.asset_id')
            ->select(
                'assets.id',
                'assets.name',
                DB::raw('SUM(user_inventories.quantity) as total_inventory'),
                DB::raw('COUNT(DISTINCT users.id) as users_with_inventory')
            )
            ->where('assets.category', 'Durable')
            ->whereIn('users.county_id', $assignedCounties)
            ->groupBy('assets.id', 'assets.name')
            ->get();
    }

    private function getCountyCoordinatorView($user)
    {
        return DB::table('user_inventories')
            ->join('users', 'users.id', '=', 'user_inventories.user_id')
            ->join('assets', 'assets.id', '=', 'user_inventories.asset_id')
            ->select(
                'assets.id',
                'assets.name',
                DB::raw('SUM(user_inventories.quantity) as total_inventory'),
                DB::raw('COUNT(DISTINCT users.id) as users_with_inventory')
            )
            ->where('assets.category', 'Durable')
            ->where('users.county_id', $user->county_id)
            ->groupBy('assets.id', 'assets.name')
            ->get();
    }

    private function getSupervisorView($user)
    {
        return DB::table('user_inventories')
            ->join('users', 'users.id', '=', 'user_inventories.user_id')
            ->join('assets', 'assets.id', '=', 'user_inventories.asset_id')
            ->select(
                'assets.id',
                'assets.name',
                DB::raw('SUM(user_inventories.quantity) as total_inventory'),
                DB::raw('COUNT(DISTINCT users.id) as users_with_inventory')
            )
            ->where('assets.category', 'Durable')
            ->where('users.supervisor_id', $user->id)
            ->groupBy('assets.id', 'assets.name')
            ->get();
    }

    private function getUserInventoryView($user)
    {
        return DB::table('user_inventories')
            ->join('assets', 'assets.id', '=', 'user_inventories.asset_id')
            ->leftJoin('asset_assignments', function($join) use ($user) {
                $join->on('assets.id', '=', 'asset_assignments.asset_id')
                    ->where('asset_assignments.assigned_to', $user->id)
                    ->where('asset_assignments.assignment_status', 'Assigned');
            })
            ->select(
                'assets.id',
                'assets.name',
                'user_inventories.quantity as inventory_quantity',
                DB::raw('COUNT(asset_assignments.id) as assigned_to_me')
            )
            ->where('assets.category', 'Durable')
            ->where('user_inventories.user_id', $user->id)
            ->groupBy('assets.id', 'assets.name', 'user_inventories.quantity')
            ->get();
    }
}