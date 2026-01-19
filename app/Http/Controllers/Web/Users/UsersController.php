<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vanguard\Events\User\Deleted;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\CreateUserRequest;
use Vanguard\Repositories\Country\CountryRepository;
use Vanguard\Repositories\County\CountyRepository;
use Vanguard\Repositories\Subcounty\SubcountyRepository;
use Vanguard\Repositories\Ward\WardRepository;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Illuminate\Support\Facades\DB;
use Vanguard\User;
use Vanguard\County;
use Vanguard\Subcounty;
use Vanguard\Ward;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;
use Vanguard\Mail\UserCreated;
use Vanguard\Role;
use Vanguard\Services\RoleHierarchyService;
use Vanguard\Setting;
use Vanguard\Traits\AutoOnboardingTrait;
use Vanguard\UserDocument;
use Vanguard\Bank;

class UsersController extends Controller
{

    protected $users;
    protected $roles;
    protected $counties;
    protected $subcounties;
    protected $wards;
    protected $roleHierarchyService;
    protected $regionalCoordinatorRoleId;
    protected $countyCoordinatorRoleId;
    protected $supervisorRoleId;
    protected $fieldOfficerRoleId;
    public function __construct(
        UserRepository $users,
        RoleRepository $roles,
        RoleHierarchyService $roleHierarchyService,
        CountyRepository $counties,
        SubcountyRepository $subcounties,
        WardRepository $wards
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->counties = $counties;
        $this->subcounties = $subcounties;
        $this->wards = $wards;
        $this->roleHierarchyService = $roleHierarchyService;
        $this->regionalCoordinatorRoleId = Role::where('name', 'Regional_Coordinator')->value('id');
        $this->countyCoordinatorRoleId = Role::where('name', 'County_Coordinator')->value('id');
        $this->supervisorRoleId = Role::where('name', 'Supervisor')->value('id');
        $this->fieldOfficerRoleId = Role::where('name', 'Field_Officer')->value('id');
        $this->middleware('permission:users.manage', ['only' => ['index', 'importUsers']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.manage', ['only' => ['destroy']]);
    }

    // public function index(Request $request)
    // {
    //     $user = Auth::user();
    //     $query = User::query();

    //     if (!$user->isAdmin() && !$user->hasRole('Manager')) {
    //         $query->whereHas('role', function ($q) use ($user) {
    //             $q->where('hierarchy', '>', $user->role->hierarchy);
    //         });

    //         if ($user->role->name === 'RegionalCoordinator') {
    //             $query->whereHas('county', function ($q) use ($user) {
    //                 $q->where('region_id', $user->county->region_id);
    //             });
    //         } elseif ($user->role->name === 'CountyCoordinator') {
    //             $query->where('county_id', $user->county_id);
    //         } elseif ($user->role->name === 'Supervisor') {
    //             $query->where('subcounty_id', $user->subcounty_id);
    //         } 
    //     }

    //     // Apply search filter
    //     if ($request->filled('search')) {
    //         $search = $request->input('search');
    //         $query->where(function ($q) use ($search) {
    //             $q->where('first_name', 'like', "%{$search}%")
    //               ->orWhere('last_name', 'like', "%{$search}%")
    //               ->orWhere('email', 'like', "%{$search}%");
    //         });
    //     }

    //     // Apply role filter
    //     if ($request->filled('role')) {
    //         $query->where('role_id', $request->input('role'));
    //     }

    //     // Apply status filter
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->input('status'));
    //     }

    //     $users = $query->paginate(20);
    //     $roles = Role::all();
    //     $statuses = ['' => __('All')] + UserStatus::lists();

    //     return view('user.list', compact('users', 'roles', 'statuses'));
    // }

    // public function index(Request $request)
    // {
    //     $query = $this->buildQuery($request);
    //     $users = $query->paginate(20);

    //     $roles = Role::all();
    //     $statuses = ['' => __('All')] + UserStatus::lists();

    //     return view('user.list', compact('users', 'roles', 'statuses'));
    // }
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = User::with(['role', 'county']);

        // Base query based on user role
        if ($currentUser->isAdmin() || $currentUser->hasRole('Manager') || $currentUser->hasRole('Finance')) {
            // Admin and Manager can see all users
            $availableCounties = County::orderBy('name')->get();
        } elseif ($currentUser->role->name === 'Regional_Coordinator') {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereIn('county_id', $assignedCountyIds)
                ->whereHas('role', function ($q) {
                    $q->whereIn('name', ['County_Coordinator', 'Supervisor', 'Field_Officer', 'User']);
                });
            $availableCounties = County::whereIn('id', $assignedCountyIds)->orderBy('name')->get();
        } elseif ($currentUser->role->name === 'County_Coordinator') {
            $query->where('county_id', $currentUser->county_id)
                ->whereHas('role', function ($q) {
                    $q->whereIn('name', ['Supervisor', 'Field_Officer', 'User']);
                });
            $availableCounties = County::where('id', $currentUser->county_id)->get();
        } elseif ($currentUser->role->name === 'Supervisor') {
            $query->where('supervisor_id', $currentUser->id)
                ->whereHas('role', function ($q) {
                    $q->where('name', 'Field_Officer');
                });
            $availableCounties = County::where('id', $currentUser->county_id)->get();
        } else {
            // Other roles can only see themselves
            $query->where('id', $currentUser->id);
            $availableCounties = County::where('id', $currentUser->county_id)->get();
        }

        // Apply county filter if selected
        if ($request->filled('county_id')) {
            // Ensure user has access to the selected county
            if (
                $currentUser->isAdmin() ||
                $currentUser->hasRole('Manager') ||
                ($currentUser->role->name === 'Regional_Coordinator' && $currentUser->counties->contains($request->county_id)) ||
                $currentUser->county_id === (int)$request->county_id
            ) {
                $query->where('county_id', $request->county_id);
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role_id', $request->input('role'));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(20);
        $roles = Role::all();
        $statuses = ['' => __('All')] + UserStatus::lists();
        $counties = $availableCounties;

        return view('user.list', compact('users', 'roles', 'statuses', 'counties'));
    }

    public function search(Request $request)
    {
        $query = $this->buildQuery($request);
        $count = $query->count();
        $total = User::count();
        $searchedCount = $count;

        if ($count === 0) {
            $searchedCount = $query->getQuery()->offset;
        }

        return response()->json([
            'count' => $count,
            'total' => $total,
            'searchedCount' => $searchedCount
        ]);
    }

    public function deepSearch(Request $request)
    {
        $query = User::query();
        $this->applySearchFilters($request, $query);
        $users = $query->paginate(20);

        return response()->json([
            'users' => $users,
            'count' => $users->total()
        ]);
    }

    public function updateSensitiveInfo(Request $request, User $user)
    {
        $this->authorize('updateSensitiveInfo', $user);

        $request->validate([
            'id_number' => 'required|string|max:255',
            'kra_pin' => 'required|string|max:255',
        ]);

        $userDocument = UserDocument::firstOrNew(['user_id' => $user->id]);
        $userDocument->id_number = $request->id_number;
        $userDocument->kra_pin = $request->kra_pin;
        $userDocument->save();

        return redirect()->back()->with('success', __('Sensitive information updated successfully.'));
    }

    protected function buildQuery(Request $request)
    {
        $query = User::query()->with(['county', 'role']); // Changed 'roles' to 'role'

        $this->applyRoleRestrictions($query);
        $this->applySearchFilters($request, $query);

        return $query;
    }

    protected function applyRoleRestrictions($query)
    {
        $user = Auth::user();

        if ($user->isAdmin() || $user->hasRole('Manager')) {
            return;
        }

        $subordinateRoles = $this->roleHierarchyService->hierarchy[$user->role->name] ?? [];

        $query->where(function ($q) use ($user, $subordinateRoles) {
            $q->whereIn('role_id', Role::whereIn('name', $subordinateRoles)->pluck('id'))
                ->orWhere('id', $user->id);
        });

        if ($user->role->name === 'Regional_Coordinator') {
            $query->whereHas('county', function ($q) use ($user) {
                $q->whereIn('id', $user->counties->pluck('id'));
            });
        } elseif ($user->role->name === 'County_Coordinator') {
            $query->where('county_id', $user->county_id);
        } elseif ($user->role->name === 'Supervisor') {
            $query->where('subcounty_id', $user->subcounty_id);
        }
    }

    protected function applySearchFilters(Request $request, $query)
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
    }


    public function assignSubordinates(Request $request)
    {
        $user = Auth::user();
        $subordinateRole = Role::find($request->role_id);

        $query = User::where('role_id', $request->role_id)
            ->whereNull('supervisor_id');

        if (!$user->isAdmin() && !$user->hasRole('Manager')) {
            if ($user->role->name === 'RegionalCoordinator') {
                $query->whereHas('county', function ($q) use ($user) {
                    $q->where('region_id', $user->county->region_id);
                });
            } elseif ($user->role->name === 'CountyCoordinator') {
                $query->where('county_id', $user->county_id);
            } elseif ($user->role->name === 'Supervisor') {
                $query->where('subcounty_id', $user->subcounty_id);
            }
        }

        $subordinates = $query->get();

        return view('user.assign_subordinates', compact('subordinates', 'subordinateRole'));
    }

    public function updateAssignments(Request $request)
    {
        $user = auth()->user();
        $assignedUsers = User::whereIn('id', $request->assigned_users)->get();

        foreach ($assignedUsers as $assignedUser) {
            if ($user->canSupervise($assignedUser)) {
                $assignedUser->supervisor_id = $user->id;
                $assignedUser->save();
            }
        }

        return redirect()->route('profile.show')->with('success', 'Assignments updated successfully.');
    }


    // public function show(User $user): View
    // {
    //     $roles = $this->roles->all()->pluck('display_name', 'id');
    //     $statuses = UserStatus::lists();
    //     $counties = $this->counties->lists();
    //     $subcounties = $user->county_id ? $this->subcounties->lists($user->county_id) : collect();
    //     $wards = $user->subcounty_id ? $this->wards->lists($user->subcounty_id) : collect();

    //     $authUser = Auth::user();
    //     $canAssignSubordinates = $authUser->isAdmin() || $authUser->hasRole('Manager') || $authUser->hasRole('RegionalCoordinator') || $authUser->hasRole('CountyCoordinator');

    //     $subordinates = collect();
    //     $subordinateRole = null;

    //     if ($canAssignSubordinates) {
    //         $subordinateRole = $this->getSubordinateRole($user);
    //         $subordinates = $this->getAssignableSubordinates($user);
    //     }

    //     return view('user.show', [
    //         'user' => $user,
    //         'roles' => $roles,
    //         'statuses' => $statuses,
    //         'counties' => [0 => __('Select a County')] + $counties->toArray(),
    //         'subcounties' => $subcounties,
    //         'wards' => $wards,
    //         'subordinates' => $subordinates,
    //         'subordinateRole' => $subordinateRole,
    //         'canAssignSubordinates' => $canAssignSubordinates,
    //         'authUser' => $authUser
    //     ]);
    // }

    public function view(User $user): View
    {
        $roles = $this->roles->all()->pluck('display_name', 'id');
        $statuses = UserStatus::lists();
        $counties = $this->counties->lists();

        // Get subcounties if county is selected
        $subcounties = collect();
        if ($user->county_id) {
            $subcounties = $this->subcounties->lists($user->county_id);
        }

        // Get wards if subcounty is selected
        $wards = collect();
        if ($user->subcounty_id) {
            $wards = $this->wards->lists($user->subcounty_id);
        }

        $authUser = Auth::user();
        $canAssignSubordinates = $authUser->isAdmin() ||
            $authUser->hasRole('Manager') ||
            $authUser->hasRole('Regional_Coordinator') ||
            $authUser->hasRole('County_Coordinator');

        $subordinates = collect();
        $subordinateRole = null;

        if ($canAssignSubordinates) {
            $subordinateRole = $this->getSubordinateRole($user);
            $subordinates = $this->getAssignableSubordinates($user);
        }

        try {
            $activities = $user->activities()->latest()->limit(10)->get();
        } catch (\Exception $e) {
            \Log::error('Error fetching user activities: ' . $e->getMessage());
            $activities = collect(); // Provide an empty collection if activities are not available
        }

        return view('user.view', [
            'user' => $user,
            'roles' => $roles,
            'statuses' => $statuses,
            'counties' => [0 => __('Select a County')] + $counties->toArray(),
            'subcounties' => $subcounties,
            'wards' => $wards,
            'subordinates' => $subordinates,
            'subordinateRole' => $subordinateRole,
            'canAssignSubordinates' => $canAssignSubordinates,
            'authUser' => $authUser,
            'activities' => $activities
        ]);
    }


    private function getSubordinateRole($user)
    {
        $roleHierarchy = [
            'Admin' => 'RegionalCoordinator',
            'Manager' => 'RegionalCoordinator',
            'RegionalCoordinator' => 'CountyCoordinator',
            'CountyCoordinator' => 'Supervisor'
        ];

        return Role::where('name', $roleHierarchy[$user->role->name] ?? null)->first();
    }

    private function getAssignableSubordinates($user)
    {
        $subordinateRole = $this->getSubordinateRole($user);

        if (!$subordinateRole) {
            return collect();
        }

        $query = User::where('role_id', $subordinateRole->id)
            ->whereNull('supervisor_id');

        if ($user->hasRole('RegionalCoordinator')) {
            $query->whereHas('county', function ($q) use ($user) {
                $q->where('region_id', $user->county->region_id);
            });
        } elseif ($user->hasRole('CountyCoordinator')) {
            $query->where('county_id', $user->county_id);
        }

        return $query->get();
    }

    /**
     * Parse countries into an array that also has a blank
     * item as first element, which will allow users to
     * leave the country field unpopulated.
     */
    private function parseCountries(CountryRepository $countryRepository): array
    {
        return ['' => __('Select a Country')] + $countryRepository->lists()->toArray();
    }

    private function parseCounties(CountyRepository $countyRepository): array
    {
        return $countyRepository->lists()->toArray();
    }



    private function parseSubcounties(SubcountyRepository $subcountyRepository, ?string $countyId): array
    {
        if ($countyId === null) {
            return [];
        }
        return $subcountyRepository->lists($countyId)->toArray();
    }

    private function parseWards(WardRepository $wardRepository, ?string $subcountyId): array
    {
        if ($subcountyId === null) {
            return [];
        }
        return $wardRepository->lists($subcountyId)->toArray();
    }


    public function store(CreateUserRequest $request): RedirectResponse
    {
        \Log::info('Raw request data:', $request->all());

        $data = $request->validated();

        // Set default values for fields not in the request
        $data['status'] = $data['status'] ?? UserStatus::ACTIVE;
        $data['email_verified_at'] = now();
        $data['role_status'] = 0;

        // Handle role-specific fields
        $role = Role::findOrFail($request->role_id);
        $counties = [];
        switch ($role->name) {
            case 'Regional_Coordinator':
                $counties = $request->input('counties', []);
                unset($data['county_id'], $data['subcounty_id'], $data['ward_id']);
                break;
            case 'County_Coordinator':
                $data['county_id'] = $request->input('county_id');
                unset($data['subcounty_id'], $data['ward_id']);
                break;
            case 'Supervisor':
                $data['county_id'] = $request->input('county_id');
                $data['subcounty_id'] = $request->input('subcounty_id');
                unset($data['ward_id']);
                break;
            case 'Field_Officer':
                $data['county_id'] = $request->input('county_id');
                $data['subcounty_id'] = $request->input('subcounty_id');
                $data['ward_id'] = $request->input('ward_id');
                break;
        }

        \Log::info('User data before creation:', $data);

        // Create the user
        $user = $this->users->create($data);

        \Log::info('User created:', $user->toArray());

        // Sync counties for Regional Coordinator
        if ($role->name === 'Regional_Coordinator' && !empty($counties)) {
            try {
                $user->counties()->sync($counties);
                \Log::info('Counties synced for user:', ['user_id' => $user->id, 'counties' => $counties]);
            } catch (\Exception $e) {
                \Log::error('Error syncing counties for user:', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        $emailConfirmationEnabled = Setting::get('reg_email_confirmation', false);

        if ($emailConfirmationEnabled) {
            try {
                Mail::to($user->email)->send(new UserCreated($user));
                \Log::info('User creation email sent:', ['user_id' => $user->id, 'email' => $user->email]);
            } catch (\Exception $e) {
                \Log::error('Error sending user creation email:', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        } else {
            \Log::info('User creation email not sent (email confirmation disabled):', ['user_id' => $user->id, 'email' => $user->email]);
        }
        // Send email notification
        return redirect()->route('users.index')
            ->withSuccess(__('User created successfully.'));
    }

    public function create(
        CountryRepository $countryRepository,
        RoleRepository $roleRepository,
        CountyRepository $countyRepository,
        SubcountyRepository $subcountyRepository,
        WardRepository $wardRepository
    ): View {
        $roles = $roleRepository->all()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name
            ];
        });
        $countries = $this->parseCountries($countryRepository);
        $counties = $this->parseCounties($countyRepository);
        $statuses = UserStatus::lists();

        return view('user.add', [
            'countries' => $countries,
            'roles' => $roles,
            'statuses' => $statuses,
            'counties' => $counties,
            'subcounties' => [],
            'wards' => [],
        ]);
    }

    public function edit(
        User $user,
        RoleRepository $roleRepository,
        CountyRepository $countyRepository,
        SubcountyRepository $subcountyRepository,
        WardRepository $wardRepository
    ): View {
        try {
            // Fetch and format roles
            $roles = $roleRepository->all()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name
                ];
            });

            // Fetch location data
            $counties = $countyRepository->lists();
            $subcounties = $user->county_id
                ? $subcountyRepository->lists($user->county_id)
                : collect();
            $wards = $user->subcounty_id
                ? $wardRepository->lists($user->subcounty_id)
                : collect();

            // Get user statuses and check permissions
            $statuses = UserStatus::lists();
            $authUser = auth()->user();
            $canViewSensitiveInfo = $authUser->isAdmin() || $authUser->hasRole('Manager');

            // Build base view data
            $viewData = [
                'user' => $user,
                'roles' => $roles,
                'statuses' => $statuses,
                'counties' => $counties->toArray(),
                'subcounties' => $subcounties->toArray(),
                'wards' => $wards->toArray(),
                'canViewSensitiveInfo' => $canViewSensitiveInfo,
                'banks' => \Vanguard\Bank::orderBy('name')->get(),

            ];

            // Add Regional Coordinator specific data
            if ($user->role->name === 'Regional_Coordinator') {
                $viewData['assignedCounties'] = $user->counties->pluck('id')->toArray();
            }

            // Add sensitive information if authorized
            if ($canViewSensitiveInfo) {
                $viewData['bankDetails'] = $user->bankDetails;
                $viewData['userDocuments'] = $user->documents;
            }

            return view('user.edit', $viewData);
        } catch (\Exception $e) {
            \Log::error('Error in user edit page:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('users.index')
                ->withErrors(__('Error loading user edit page. Please try again.'));
        }
    }

    public function update(Request $request, User $user)
    {
        DB::beginTransaction();
        try {
            // Debug incoming request
            \Log::info('Update request received:', [
                'request_data' => $request->all(),
                'current_user_data' => [
                    'role' => $user->role->name,
                    'county_id' => $user->county_id,
                    'subcounty_id' => $user->subcounty_id
                ]
            ]);

            // Get current role and new role
            $currentRole = $user->role;
            $newRole = Role::findOrFail($request->role_id);

            // Basic user data update
            $userData = $request->only([
                'first_name',
                'last_name',
                'email',
                'phone',
                'status'
            ]);

            // Initialize location data with current values to prevent nulling
            $locationData = [
                'role_id' => $newRole->id,
                'county_id' => $user->county_id,    // Keep existing value by default
                'subcounty_id' => $user->subcounty_id,
                'ward_id' => $user->ward_id
            ];

            // Clear existing locations if role is changing
            if ($currentRole->id !== $newRole->id) {
                $user->counties()->detach();
                // Reset location data when role changes
                $locationData = [
                    'role_id' => $newRole->id,
                    'county_id' => null,
                    'subcounty_id' => null,
                    'ward_id' => null
                ];
            }

            // Handle location data based on new role
            switch ($newRole->name) {
                case 'Regional_Coordinator':
                    if ($request->has('counties')) {
                        $user->counties()->sync($request->input('counties', []));
                    }
                    // Clear location fields for Regional Coordinator
                    $locationData['county_id'] = null;
                    $locationData['subcounty_id'] = null;
                    $locationData['ward_id'] = null;
                    break;

                case 'County_Coordinator':
                    // Only update county_id if it's provided in the request
                    if ($request->filled('county_id')) {
                        $locationData['county_id'] = $request->input('county_id');
                    }
                    $locationData['subcounty_id'] = null;
                    $locationData['ward_id'] = null;
                    $user->counties()->detach();
                    break;

                case 'Supervisor':
                    if ($request->filled('county_id')) {
                        $locationData['county_id'] = $request->input('county_id');
                    }
                    if ($request->filled('subcounty_id')) {
                        $locationData['subcounty_id'] = $request->input('subcounty_id');
                    }
                    $locationData['ward_id'] = null;
                    $user->counties()->detach();
                    break;

                case 'Field_Officer':
                    if ($request->filled('county_id')) {
                        $locationData['county_id'] = $request->input('county_id');
                    }
                    if ($request->filled('subcounty_id')) {
                        $locationData['subcounty_id'] = $request->input('subcounty_id');
                    }
                    if ($request->filled('ward_id')) {
                        $locationData['ward_id'] = $request->input('ward_id');
                    }
                    $user->counties()->detach();
                    break;
            }

            // Debug location data before update
            \Log::info('Location data before update:', [
                'role' => $newRole->name,
                'location_data' => $locationData,
                'request_county_id' => $request->input('county_id')
            ]);

            // Update user with both basic and location data
            $user->fill(array_merge($userData, $locationData));
            $user->save();

            // Verify the update
            $user->refresh();
            \Log::info('User updated:', [
                'user_id' => $user->id,
                'old_role' => $currentRole->name,
                'new_role' => $newRole->name,
                'final_county_id' => $user->county_id,
                'final_subcounty_id' => $user->subcounty_id
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->withSuccess(__('User updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('User update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(__('Failed to update user: ') . $e->getMessage())
                ->withInput();
        }
    }

    public function assignFieldOfficer(Request $request): View
    {
        $query = User::role('Supervisor');

        // Apply search if provided
        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Apply filter if provided (e.g., by role)
        if ($request->has('filter') && $request->filter !== '') {
            if ($request->filter === 'supervisor') {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'Supervisor');
                });
            }
        }

        // Get the list of supervisors within the user's boundary
        $supervisors = $query->paginate(10);

        return view('user.profile', [
            'user' => auth()->user(),
            'supervisors' => $supervisors,
            'activeTab' => 'assign'
        ]);
    }


    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')
                ->withErrors(__('You cannot delete yourself.'));
        }

        try {
            DB::beginTransaction();

            // Remove any supervisor relationships
            if ($user->role->name === 'Supervisor') {
                // Unassign all field officers from this supervisor
                User::where('supervisor_id', $user->id)
                    ->update(['supervisor_id' => null]);
            }

            // Remove county relationships for Regional Coordinators
            if ($user->role->name === 'Regional_Coordinator') {
                $user->counties()->detach();
            }

            // Remove any other relationships
            $user->activities()->delete();  // If you have activities
            $user->documents()->delete();   // If you have documents
            $user->bankDetails()->delete(); // If you have bank details

            // Finally delete the user
            $user->delete();

            DB::commit();

            event(new Deleted($user));

            return redirect()->route('users.index')
                ->withSuccess(__('User deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('User deletion failed: ' . $e->getMessage());

            return redirect()->route('users.index')
                ->withErrors(__('Failed to delete user. Please ensure all related records are handled.'));
        }
    }

    public function getSubcounties(Request $request)
    {
        $countyId = $request->get('county_id');
        \Log::info('Fetching subcounties for county:', ['county_id' => $countyId]);

        try {
            $subcounties = DB::table('subcounties')
                ->where('county_id', $countyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            \Log::info('Found subcounties:', ['count' => count($subcounties)]);
            return response()->json($subcounties);
        } catch (\Exception $e) {
            \Log::error('Error fetching subcounties:', [
                'county_id' => $countyId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch subcounties'], 500);
        }
    }

    public function getWards(Request $request)
    {
        $subcountyId = $request->get('subcounty_id');
        \Log::info('Fetching wards for subcounty:', ['subcounty_id' => $subcountyId]);

        try {
            $wards = DB::table('wards')
                ->where('subcounty_id', $subcountyId)
                ->orderBy('name')
                ->pluck('name', 'id')
                ->toArray();

            \Log::info('Found wards:', ['count' => count($wards)]);
            return response()->json($wards);
        } catch (\Exception $e) {
            \Log::error('Error fetching wards:', [
                'subcounty_id' => $subcountyId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch wards'], 500);
        }
    }

    public function importUsers(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('import_file');
        $filePath = $file->getRealPath();

        try {
            $spreadsheet = IOFactory::load($filePath);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error loading file. Please check the file format.');
        }

        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        if (count($data) <= 1) {
            return redirect()->back()->withErrors('The uploaded file has no data.');
        }

        $headerVariants = [
            'first_name' => ['first_name', 'First Name', 'FIRST_NAME', 'first name', 'FIRST NAME'],
            'last_name' => ['last_name', 'Last Name', 'LAST_NAME', 'last name', 'LAST NAME'],
            'email' => ['email', 'Email', 'EMAIL'],
            'phone' => ['phone', 'Phone', 'PHONE', 'Contact', 'CONTACT'],
            'county_id' => ['county_id', 'County ID', 'COUNTY_ID', 'county id', 'COUNTY ID'],
        ];

        $headerMap = [];
        foreach ($headerVariants as $key => $variants) {
            foreach ($variants as $variant) {
                $index = array_search($variant, $data[0]);
                if ($index !== false) {
                    $headerMap[$key] = $index;
                    break;
                }
            }
        }

        foreach (array_keys($headerVariants) as $requiredHeader) {
            if (!isset($headerMap[$requiredHeader])) {
                return redirect()->back()->withErrors("The uploaded file is missing a required header: {$requiredHeader}");
            }
        }

        $errors = [];

        foreach ($data as $index => $row) {
            if ($index === 0) continue;

            $userData = [
                'first_name' => $row[$headerMap['first_name']] ?? null,
                'last_name' => $row[$headerMap['last_name']] ?? null,
                'email' => $row[$headerMap['email']] ?? null,
                'phone' => $row[$headerMap['phone']] ?? null,
                'county_id' => $row[$headerMap['county_id']] ?? null,
            ];

            $validator = Validator::make($userData, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'phone' => 'required|string|max:20|unique:users,phone',
                'county_id' => 'required|integer|exists:counties,id',
            ]);

            if ($validator->fails()) {
                $errors[$index + 1] = $validator->errors()->all();
                continue;
            }

            $userData['username'] = $userData['email'];
            $userData['role_id'] = 6;
            $userData['role_status'] = 0;
            $userData['password'] = ('SELISTAR');
            $userData['status'] = UserStatus::UNCONFIRMED;
            $userData['email_verified_at'] = now();

            try {
                $this->users->create($userData);
            } catch (\Exception $e) {
                $errors[$index + 1] = ['Error inserting user: ' . $e->getMessage()];
            }
        }

        if (!empty($errors)) {
            $errorMessages = [];
            foreach ($errors as $rowNumber => $rowErrors) {
                $errorMessages[] = 'Row ' . $rowNumber . ': ' . implode(', ', $rowErrors);
            }
            return redirect()->back()->withErrors('Errors occurred during import: ' . implode('; ', $errorMessages));
        }

        return redirect()->route('users.index')->withSuccess('Users imported successfully.');
    }

    public function list(Request $request)
    {
        $currentUser = Auth::user();
        $query = User::query();

        if ($currentUser->isAdmin() || $currentUser->hasRole('Manager')) {
            // Admin and Manager can see all users
        } elseif ($currentUser->role->name === 'Regional_Coordinator') {
            $assignedCountyIds = $currentUser->counties()->pluck('counties.id');
            $query->whereIn('county_id', $assignedCountyIds)
                ->whereHas('role', function ($q) {
                    $q->whereIn('name', ['County_Coordinator', 'Supervisor', 'Field_Officer']);
                });
        } elseif ($currentUser->role->name === 'County_Coordinator') {
            $query->where('county_id', $currentUser->county_id)
                ->whereHas('role', function ($q) {
                    $q->whereIn('name', ['Supervisor', 'Field_Officer']);
                });
        } elseif ($currentUser->role->name === 'Supervisor') {
            $query->where('supervisor_id', $currentUser->id)
                ->whereHas('role', function ($q) {
                    $q->where('name', 'Field_Officer');
                });
        }

        $users = $query->select('id', \DB::raw("CONCAT(first_name, ' ', last_name) AS name"))
            ->get();

        return response()->json($users);
    }
}
