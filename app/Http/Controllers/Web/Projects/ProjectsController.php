<?php

namespace Vanguard\Http\Controllers\Web\Projects;

use Vanguard\Http\Controllers\Controller;
use Vanguard\Projects;
use Vanguard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectsController extends Controller
{
   public function index()
   {
       // Get paginated projects
       $projects = Projects::orderBy('name')->paginate(20);
   
       // Get the current active project for the logged-in user

       $currentActiveProject = Auth::user()->activeProject();
   

       return view('projects.index', compact('projects', 'currentActiveProject'));
   }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'budget'      => 'nullable|numeric|min:0',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Projects::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Set a project as the active project for the current user
     */
    public function setActive(Projects $project)
    {
        $user = Auth::user();
        $projectId = $project->id;

        DB::beginTransaction();

        try {
            // Deactivate all projects for this user
            DB::table('projects_user')
                ->where('user_id', $user->id)
                ->update(['is_active_project' => false]);

            // Check if user is already attached to this project
            $exists = DB::table('projects_user')
                ->where('user_id', $user->id)
                ->where('project_id', $projectId)
                ->exists();

            if ($exists) {
                // Update existing record
                DB::table('projects_user')
                    ->where('user_id', $user->id)
                    ->where('project_id', $projectId)
                    ->update([
                        'is_active_project' => true,
                        'updated_at' => now(),
                    ]);
            } else {
                // Attach user to project with active flag
                $user->projects()->attach($projectId, [
                    'is_active_project' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Store in session for quick access
            session([
                'active_project_id'   => $projectId,
                'active_project_name' => $project->name,
            ]);

            DB::commit();

            Log::info('Active project set successfully', [
                'user_id'    => $user->id,
                'project_id' => $projectId,
            ]);

            return redirect()->back()
                ->with('success', "Project '{$project->name}' is now your active project.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to set active project', [
                'user_id'    => $user->id,
                'project_id' => $projectId,
                'error'      => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to set active project. Please try again.');
        }
    }



    /**
 * Show form to assign users to project
 */
public function assignUsers(Projects $project)
{
    \Gate::authorize('assign-project-users', $project);

    $currentUser = auth()->user();
    
    // Get assignable users based on role
    if ($currentUser->isAdmin() || $currentUser->hasRole(['Manager', 'Finance'])) {
        $availableUsers = User::whereDoesntHave('projects', function($q) use ($project) {
            $q->where('projects.id', $project->id);
        })->with('role', 'county')->orderBy('first_name')->get();
        
        $assignedUsers = $project->users()->with('role', 'county')->get();
    } elseif ($currentUser->role->name === 'Regional_Coordinator') {
        $countyIds = $currentUser->counties()->pluck('counties.id');
        
        $availableUsers = User::whereIn('county_id', $countyIds)
            ->whereDoesntHave('projects', function($q) use ($project) {
                $q->where('projects.id', $project->id);
            })
            ->with('role', 'county')
            ->orderBy('first_name')
            ->get();
            
        $assignedUsers = $project->users()
            ->whereIn('county_id', $countyIds)
            ->with('role', 'county')
            ->get();
    } elseif ($currentUser->role->name === 'County_Coordinator') {
        $availableUsers = User::where('county_id', $currentUser->county_id)
            ->whereDoesntHave('projects', function($q) use ($project) {
                $q->where('projects.id', $project->id);
            })
            ->with('role', 'county')
            ->orderBy('first_name')
            ->get();
            
        $assignedUsers = $project->users()
            ->where('county_id', $currentUser->county_id)
            ->with('role', 'county')
            ->get();
    } else {
        abort(403, 'Unauthorized');
    }

    return view('projects.assign-users', compact('project', 'availableUsers', 'assignedUsers'));
}

/**
 * Store user assignments
 */
public function storeUserAssignments(Request $request, Projects $project)
{
    $request->validate([
        'user_ids' => 'required|array',
        'user_ids.*' => 'exists:users,id',
        'set_as_active' => 'nullable|boolean'
    ]);

    try {
        DB::beginTransaction();

        foreach ($request->user_ids as $userId) {
            $project->users()->syncWithoutDetaching([
                $userId => ['is_active_project' => $request->boolean('set_as_active', false)]
            ]);
        }

        DB::commit();

        return redirect()->route('projects.assign-users', $project)
            ->with('success', count($request->user_ids) . ' user(s) assigned successfully.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to assign users: ' . $e->getMessage());
    }
}

/**
 * Remove user from project
 */
public function removeUser(Projects $project, User $user)
{
    try {
        $project->users()->detach($user->id);
        
        return back()->with('success', 'User removed from project successfully.');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to remove user: ' . $e->getMessage());
    }
}

/**
 * Search users for assignment
 */
public function searchUsers(Request $request, Projects $project)
{
    $search = $request->get('q');
    $currentUser = auth()->user();
    
    $query = User::where(function($q) use ($search) {
        $q->where('first_name', 'like', "%{$search}%")
          ->orWhere('last_name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
          ->orWhere('phone', 'like', "%{$search}%");
    })
    ->whereDoesntHave('projects', function($q) use ($project) {
        $q->where('projects.id', $project->id);
    });

    // Apply role-based filtering
    if ($currentUser->role->name === 'Regional_Coordinator') {
        $countyIds = $currentUser->counties()->pluck('counties.id');
        $query->whereIn('county_id', $countyIds);
    } elseif ($currentUser->role->name === 'County_Coordinator') {
        $query->where('county_id', $currentUser->county_id);
    }

    $users = $query->with('role', 'county')->limit(20)->get();

    return response()->json([
        'results' => $users->map(function($user) {
            return [
                'id' => $user->id,
                'text' => $user->name . ' (' . $user->email . ')',
                'role' => $user->role->display_name ?? '',
                'county' => $user->county->name ?? ''
            ];
        })
    ]);
}

    /**
     * Clear the active project for the current user
     */
    public function clearActive(Request $request)
    {
        $user = Auth::user();

        try {
            DB::table('projects_user')
                ->where('user_id', $user->id)
                ->update(['is_active_project' => false]);

            session()->forget([
                'active_project_id',
                'active_project_name',
            ]);

            Log::info('Active project cleared', [
                'user_id' => $user->id,
            ]);

            return redirect()->back()
                ->with('success', 'Active project cleared.');

        } catch (\Exception $e) {
            Log::error('Failed to clear active project', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to clear active project. Please try again.');
        }
    }
}
