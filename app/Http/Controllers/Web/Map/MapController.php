<?php

namespace Vanguard\Http\Controllers\Web\Map;

use Vanguard\Http\Controllers\Controller;
use Vanguard\MapUser;
use Vanguard\Role;
use Vanguard\County;
use Illuminate\Http\Request;
use Vanguard\Services\RoleHierarchyService;

class MapController extends Controller
{
    protected $roleHierarchyService;

    public function __construct(RoleHierarchyService $roleHierarchyService)
    {
        $this->roleHierarchyService = $roleHierarchyService;
    }

    public function index(Request $request)
    {
        $roles = Role::all();
        $counties = County::all();

        $query = MapUser::query();

        if ($request->filled('role')) {
            $query->where('role_id', $request->input('role'));
        }

        if ($request->filled('county')) {
            $query->where('county_id', $request->input('county'));
        }

        $mapUsers = $query->with(['user', 'role', 'county'])->get();

        return view('map.index', compact('mapUsers', 'roles', 'counties'));
    }
}
