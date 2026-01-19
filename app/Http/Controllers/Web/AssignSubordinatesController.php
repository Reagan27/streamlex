<?php


namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\County;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Role;
use Vanguard\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AssignSubordinatesController extends Controller
{
    public function __construct()
    {
        // Allow access to authenticated users only.
        $this->middleware('auth');

        $this->middleware('permission:subordinates.assignment');
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = $user->role->name;    
        switch ($roleName) {
            case 'Admin':
            case 'Manager':
                return $this->adminView($request);
            case 'Regional_Coordinator':
                return $this->regionalCoordinatorView($request, $user);
            case 'County_Coordinator':
                return $this->countyCoordinatorView($request, $user);
            case 'Supervisor':
                return $this->supervisorView($user);
            case 'Field_Officer':
                return $this->fieldOfficerView($user);
            default:
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
    }

    private function getFilteredSupervisors(User $user)
    {
        $query = User::whereHas('role', function($q) {
            $q->where('name', 'Supervisor');
        })->with('county');

        switch ($user->role->name) {
            case 'Admin':
            case 'Manager':
                // No additional filtering needed
                break;
            case 'Regional_Coordinator':
                $countyIds = $user->counties->pluck('id');
                $query->whereIn('county_id', $countyIds);
                break;
            case 'County_Coordinator':
                $query->where('county_id', $user->county_id);
                break;
            default:
                $query->where('id', null); // Return empty result for other roles
        }

        return $query->get();
    }

    public function getFieldOfficers(Request $request)
{
    $supervisorId = $request->input('supervisor_id');
    $supervisor = User::findOrFail($supervisorId);

    $fieldOfficers = User::where('role_id', Role::where('name', 'Field_Officer')->first()->id)
        ->where('county_id', $supervisor->county_id)
        ->whereNull('supervisor_id')
        ->get(['id', 'first_name', 'last_name']);

    return response()->json($fieldOfficers);
}

    private function getCommonViewData(Request $request, $query)
    {
        $roles = Role::pluck('name', 'id')->toArray();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('role', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('county', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
    
        $users = $query->paginate(15)->appends($request->except('page'));
        $supervisors = $this->getFilteredSupervisors(Auth::user());

        $filteredFieldOfficers = User::whereHas('role', function($q) {
            $q->where('name', 'Field_Officer');
        })->whereNull('supervisor_id')->with('county')->get();
    
        return compact('roles', 'users', 'supervisors', 'filteredFieldOfficers');
    }

    private function adminView(Request $request)
    {
        $counties = County::orderBy('name')->pluck('name', 'id')->toArray();
        $query = User::with(['role', 'county']);
    
        if ($request->filled('county')) {
            $query->where('county_id', $request->county);
        }
    
        $data = $this->getCommonViewData($request, $query);
        $data['counties'] = $counties;
    
        return view('assign-subordinates.index', $data);
    }


    private function regionalCoordinatorView(Request $request, User $user)
{
    $counties = $user->counties()->pluck('name', 'counties.id');
    $query = User::whereHas('county', function($q) use ($counties) {
        $q->whereIn('id', $counties->keys());
    });

    if ($request->filled('county')) {
        $query->where('county_id', $request->county);
    }

    $data = $this->getCommonViewData($request, $query);
    $data['counties'] = $counties;

    return view('assign-subordinates.index', $data);
}

    private function countyCoordinatorView(Request $request, User $user)
{
    $query = User::where('county_id', $user->county_id);
    $data = $this->getCommonViewData($request, $query);
    $data['counties'] = [$user->county_id => $user->county->name];

    return view('assign-subordinates.index', $data);
}


public function unassignFieldOfficer($id)
{
    try {
        DB::beginTransaction();

        $fieldOfficer = User::findOrFail($id);
        
        if ($fieldOfficer->role->name !== 'Field_Officer') {
            throw new \Exception('User is not a Field Officer');
        }

        $oldSupervisorId = $fieldOfficer->supervisor_id;
        
        // Use null instead of empty string for supervisor_id
        $fieldOfficer->supervisor_id = null;
        $fieldOfficer->save();

        // Use Laravel's built-in logging instead of activity()
        Log::info('Field officer unassigned', [
            'field_officer_id' => $id,
            'previous_supervisor_id' => $oldSupervisorId,
            'performed_by' => auth()->id()
        ]);

        DB::commit();
        return redirect()->back()->with('success', 'Field Officer unassigned successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to unassign field officer:', [
            'field_officer_id' => $id,
            'error' => $e->getMessage()
        ]);
        
        return redirect()->back()->with('error', 'Unable to unassign Field Officer: ' . $e->getMessage());
    }
}

    private function supervisorView(User $user)
    {
        $fieldOfficers = User::where('supervisor_id', $user->id)->get();   
        return view('assign-subordinates.supervisor', compact('fieldOfficers'));
    }

    private function fieldOfficerView(User $user)
    {
        $supervisor = User::find($user->supervisor_id);
        return view('assign-subordinates.field_officer', compact('supervisor'));
    }

    public function assignFieldOfficers(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
            'field_officer_ids' => 'required|array',
            'field_officer_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->field_officer_ids)
            ->update(['supervisor_id' => $request->supervisor_id]);

        return redirect()->back()->with('success', 'Field Officers assigned successfully.');
    }

}
