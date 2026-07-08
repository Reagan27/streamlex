<?php
namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Vanguard\AdminContract;
use Vanguard\ContractVersion;
use Vanguard\County;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Role;
use Vanguard\Services\ContractPdfService;
use Vanguard\Services\RoleHierarchyService;
use Vanguard\User;
use Vanguard\UserContractSignature;
use Vanguard\Projects;
use Vanguard\Contract;

class ContractController extends Controller
{
    protected $contractPdfService;

    public function __construct(ContractPdfService $contractPdfService)
    {
        $this->contractPdfService = $contractPdfService;
    }

    public function show(AdminContract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    public function contractsList(Request $request)
    {
        $currentUser = auth()->user();
        $query = AdminContract::query()->where('status', '!=', 'draft');

        if (!$currentUser->hasRole(['Admin', 'Manager'])) {
            $roleHierarchyService = app(RoleHierarchyService::class);
            $subordinateRoles = $roleHierarchyService->getAllSubordinateRoles($currentUser->role->name);

            $query->whereIn('role_id', function ($sub) use ($subordinateRoles) {
                $sub->select('id')
                    ->from('roles')
                    ->whereIn('name', $subordinateRoles);
            });

            if ($currentUser->role && $currentUser->role->name === 'Regional_Coordinator') {
                $assignedCountyIds = $currentUser->counties
                    ? $currentUser->counties->pluck('id')->toArray()
                    : [];
                $query->whereHas('counties', function ($q) use ($assignedCountyIds) {
                    $q->whereIn('counties.id', $assignedCountyIds);
                });
            } else {
                $query->whereHas('counties', function ($q) use ($currentUser) {
                    $q->where('counties.id', $currentUser->county_id);
                });
            }
        }

        if ($request->filled('county')) {
            $countyId = $request->input('county');
            $query->whereHas('counties', function ($q) use ($countyId) {
                $q->where('counties.id', $countyId);
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('userContractSignatures.user', function ($uq) use ($search) {
                        $uq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                });
            }

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->input('date_range'));
            if (count($dates) === 2) {
                $query->whereBetween('start_date', [$dates[0], $dates[1]]);
            }
        }

        $contracts = $query
            ->with(['userContractSignatures' => function ($q) {
                $q->whereIn('status', ['approved', 'accepted', 'declined', 'terminated', 'inactive'])
                  ->with('user.role', 'user.county');
            }])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $counties = County::orderBy('name')->get();
        $roles    = Role::orderBy('display_name')->get();
        $projects = Projects::orderBy('name')->get();

        return view('contractsList.index', compact('contracts', 'counties', 'roles', 'projects'));
    }

    public function index(Request $request)
    {
        $query = AdminContract::query()->where('status', '!=', 'draft');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%$search%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $contracts = $query->orderByDesc('created_at')->get()->map(function ($contract) {
            if ($contract->end_date) {
                $days = now()->startOfDay()->diffInDays(Carbon::parse($contract->end_date)->endOfDay(), false);
                $contract->remaining_days = $days < 0 ? 'Expired' : (int)$days;
            } else {
                $contract->remaining_days = null;
            }
            $contract->start_date = $contract->start_date
                ? Carbon::parse($contract->start_date)
                : null;
            return $contract;
        });

        return view('contracts.index', compact('contracts'));
    }

    public function store(Request $request)
    {
        $category = $request->input('contract_category', 'group');

        if ($category === 'individual') {
            $validatedData = $request->validate([
                'title'                => 'required|string|max:255',
                'ind_start_date'       => 'required|date',
                'ind_end_date'         => 'required|date|after_or_equal:ind_start_date',
                'ind_number_of_days'   => 'required|integer',
                'description'          => 'required|string',
                'user_id'              => 'required|exists:users,id',
                'engagement_type'      => 'required|string',
                'duration_type'        => 'required|string',
                'status'               => 'required|in:draft,published,dropped,inactive',
                'authority_name'       => 'required|string|max:255',
                'authority_designation'=> 'required|string|max:255',
                'authority_signature'  => 'required|string',
                'project_id'           => 'nullable|exists:projects,id',
            ]);

            try {
                DB::beginTransaction();

                $user = User::firstOrCreate(
                    ['id' => $validatedData['user_id']],
                    [
                        'first_name' => $request->input('user_first_name', 'Default'),
                        'last_name'  => $request->input('user_last_name', 'User'),
                        'email'      => $request->input('user_email', 'default@example.com'),
                        'password'   => bcrypt('password'),
                    ]
                );

                $calculatedEndDate = Carbon::parse($validatedData['ind_start_date'])
                    ->addDays($validatedData['ind_number_of_days'] - 1);

                $contractData = [
                    'contract_category'    => 'individual',
                    'title'                => $validatedData['title'],
                    'start_date'           => $validatedData['ind_start_date'],
                    'end_date'             => $calculatedEndDate->toDateString(),
                    'number_of_days'       => $validatedData['ind_number_of_days'],
                    'description'          => $validatedData['description'],
                    'user_id'              => $user->id,
                    'engagement_type'      => $validatedData['engagement_type'],
                    'duration_type'        => $validatedData['duration_type'],
                    'status'               => $validatedData['status'],
                    'authority_name'       => $validatedData['authority_name'],
                    'authority_designation'=> $validatedData['authority_designation'],
                    'authority_signature'  => $validatedData['authority_signature'],
                    'active_for_onboarding'=> $request->has('active_for_onboarding'),
                    'project_id'           => $validatedData['project_id'],
                ];

                $contract = AdminContract::create($contractData);

                ContractVersion::create([
                    'contract_id'          => $contract->id,
                    'status'               => $contract->status,
                    'description'          => $contract->description,
                    'authority_signature'  => $contract->authority_signature,
                    'authority_name'       => $contract->authority_name,
                    'authority_designation'=> $contract->authority_designation,
                    'changed_by'           => auth()->id(),
                ]);

                DB::commit();
                return redirect()->route('contracts.index')
                    ->with('success', 'Contract created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Contract creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()
                    ->with('error', 'Failed to create contract: ' . $e->getMessage());
            }

        } else {
            $validatedData = $request->validate([
                'title'                => 'required|string|max:255',
                'start_date'           => 'required|date',
                'number_of_days'       => 'required|integer',
                'description'          => 'required|string',
                'role_id'              => 'required|exists:roles,id',
                'counties'             => 'required|array',
                'counties.*'           => 'exists:counties,id',
                'status'               => 'required|in:draft,published,dropped,inactive',
                'authority_name'       => 'required|string|max:255',
                'authority_designation'=> 'required|string|max:255',
                'authority_signature'  => 'required|string',
                'project_id'           => 'nullable|exists:projects,id',
            ]);

            try {
                DB::beginTransaction();

                $endDate  = $this->getEndDate($validatedData['start_date'], $validatedData['number_of_days']);
                $contract = AdminContract::create([
                    'contract_category'    => 'group',
                    'title'                => $validatedData['title'],
                    'start_date'           => $validatedData['start_date'],
                    'end_date'             => $endDate,
                    'number_of_days'       => $validatedData['number_of_days'],
                    'description'          => $validatedData['description'],
                    'role_id'              => $validatedData['role_id'],
                    'status'               => $validatedData['status'],
                    'authority_name'       => $validatedData['authority_name'],
                    'authority_designation'=> $validatedData['authority_designation'],
                    'authority_signature'  => $validatedData['authority_signature'],
                    'active_for_onboarding'=> $request->has('active_for_onboarding'),
                    'project_id'           => $validatedData['project_id'],
                ]);

                ContractVersion::create([
                    'contract_id'          => $contract->id,
                    'status'               => $contract->status,
                    'description'          => $contract->description,
                    'authority_signature'  => $contract->authority_signature,
                    'authority_name'       => $contract->authority_name,
                    'authority_designation'=> $contract->authority_designation,
                    'change_reason'        => 'Initial contract creation',
                    'changed_by'           => auth()->id(),
                    'project_id'           => $contract->project_id,
                ]);

                $contract->counties()->sync($request->input('counties', []));

                DB::commit();
                return redirect()->route('contracts.index')
                    ->with('success', 'Contract created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Contract creation failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()
                    ->with('error', 'Failed to create contract: ' . $e->getMessage());
            }
        }
    }

    public function update(Request $request, AdminContract $contract)
    {
        $validatedData = [];

        if ($request->input('contract_category') === 'individual') {
            $validatedData = $request->validate([
                'user_id'              => 'required|exists:users,id',
                'engagement_type'      => 'required|string',
                'duration_type'        => 'required|string',
                'duration_amount'      => 'required|integer|min:1',
                'start_date'           => 'required|date',
                'end_date'             => 'nullable|date|after_or_equal:start_date',
                'description'          => 'required|string',
                'status'               => 'required|in:draft,published,dropped',
                'authority_name'       => 'required|string|max:255',
                'authority_designation'=> 'required|string|max:255',
                'authority_signature'  => 'required|string',
                'change_reason'        => 'required|string',
                'project_id'           => 'nullable|exists:projects,id',
            ]);

            $endDate = $this->getEndDate($validatedData['start_date'], $validatedData['duration_amount']);

            $contract->update([
                'user_id'               => $validatedData['user_id'],
                'engagement_type'       => $validatedData['engagement_type'],
                'duration_type'         => $validatedData['duration_type'],
                'number_of_days'        => $validatedData['duration_amount'],
                'start_date'            => $validatedData['start_date'],
                'end_date'              => $validatedData['end_date'] ?? $endDate,
                'description'           => $validatedData['description'],
                'status'                => $validatedData['status'],
                'authority_name'        => $validatedData['authority_name'],
                'authority_designation' => $validatedData['authority_designation'],
                'authority_signature'   => $validatedData['authority_signature'],
                'project_id'            => $validatedData['project_id'],
                'active_for_onboarding' => $request->has('active_for_onboarding'),
            ]);

        } else {
            $validatedData = $request->validate([
                'role_id'               => 'required|exists:roles,id',
                'counties'              => 'required|array',
                'counties.*'            => 'exists:counties,id',
                'start_date'            => 'required|date',
                'duration_amount'       => 'required|integer',
                'duration_type'         => 'required|string',
                'description'           => 'required|string',
                'status'                => 'required|in:draft,published,dropped',
                'authority_name'        => 'required|string|max:255',
                'authority_designation' => 'required|string|max:255',
                'authority_signature'   => 'required|string',
                'change_reason'         => 'required|string',
                'project_id'            => 'nullable|exists:projects,id',
            ]);

            $endDate = $this->getEndDate($validatedData['start_date'], $validatedData['duration_amount']);

            $contract->update([
                'role_id'               => $validatedData['role_id'],
                'start_date'            => $validatedData['start_date'],
                'end_date'              => $endDate,
                'number_of_days'        => $validatedData['duration_amount'],
                'duration_type'         => $validatedData['duration_type'],
                'description'           => $validatedData['description'],
                'status'                => $validatedData['status'],
                'authority_name'        => $validatedData['authority_name'],
                'authority_designation' => $validatedData['authority_designation'],
                'authority_signature'   => $validatedData['authority_signature'],
                'project_id'            => $validatedData['project_id'],
                'active_for_onboarding' => $request->has('active_for_onboarding'),
            ]);

            $contract->counties()->sync($validatedData['counties']);
        }

        ContractVersion::create([
            'contract_id'          => $contract->id,
            'status'               => $contract->status,
            'description'          => $contract->description,
            'authority_signature'  => $contract->authority_signature,
            'authority_name'       => $contract->authority_name,
            'authority_designation'=> $contract->authority_designation,
            'change_reason'        => $validatedData['change_reason'],
            'changed_by'           => auth()->id(),
        ]);

        return redirect()->route('contracts.index')->with('success', 'Contract updated successfully.');
    }

    public function checkContractStatus(User $user)
    {
        $currentContract = UserContractSignature::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($currentContract) {
            $adminContract = AdminContract::with('counties')
                ->find($currentContract->contract_id);

            if (!$adminContract) {
                $currentContract->update([
                    'status'            => 'inactive',
                    'completion_reason' => 'Contract no longer exists',
                    'completion_date'   => now()
                ]);
                return ['active' => false, 'reason' => 'contract_not_found'];
            }

            if (in_array($currentContract->status, ['terminated', 'expired', 'inactive'])) {
                return ['active' => false, 'reason' => $currentContract->status];
            }
        }

        return ['active' => true];
    }

    private function calculateRemainingDays($contract)
    {
        if (!$contract->start_date) {
            return 'N/A';
        }

        $startDate = Carbon::parse($contract->start_date);
        $endDate   = $this->getEndDate($startDate, $contract->number_of_days);
        $today     = Carbon::now();

        if ($today > $endDate) {
            return 'Expired';
        }

        $remainingDays = 0;
        $currentDate   = Carbon::now();

        while ($currentDate->lt($endDate)) {
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $remainingDays++;
            }
            $currentDate->addDay();
        }

        return $remainingDays;
    }

    public function getDashboardStats()
    {
        $contracts        = AdminContract::all();
        $activeContracts  = 0;
        $expiredContracts = 0;

        foreach ($contracts as $contract) {
            $remainingDays = $this->calculateRemainingDays($contract);
            if ($remainingDays === 'Expired') {
                $expiredContracts++;
            } else {
                $activeContracts++;
            }
        }

        return [
            'activeContracts'  => $activeContracts,
            'expiredContracts' => $expiredContracts,
        ];
    }

    private function applyRoleBasedFilters($query, $currentUser)
    {
        $roleHierarchyService = app(RoleHierarchyService::class);
        $subordinateRoles     = $roleHierarchyService->getAllSubordinateRoles($currentUser->role->name);

        $query->whereIn('role_id', function ($sub) use ($subordinateRoles) {
            $sub->select('id')
                ->from('roles')
                ->whereIn('name', $subordinateRoles);
        });

        if ($currentUser->role->name === 'Regional_Coordinator') {
            $assignedCountyIds = $currentUser->counties->pluck('id')->toArray();
            $query->whereHas('counties', function ($q) use ($assignedCountyIds) {
                $q->whereIn('counties.id', $assignedCountyIds);
            });
        } else {
            $query->whereHas('counties', function ($q) use ($currentUser) {
                $q->where('counties.id', $currentUser->county_id);
            });
        }
    }

    public function viewContract($id)
    {
        try {
            $contractSignature = UserContractSignature::find($id);

            if ($contractSignature && $contractSignature->signature) {
                $contract = $contractSignature->contract;

                if (!$contract || $contract->status !== AdminContract::STATUS_PUBLISHED) {
                    Log::warning('Attempt to view contract with non-published status', [
                        'id'              => $id,
                        'contract_id'     => $contract ? $contract->id : null,
                        'contract_status' => $contract ? $contract->status : null
                    ]);
                    return response()->view('errors.404', ['message' => 'Contract not available.'], 404);
                }

                $consultantUser = $contractSignature->user;
                Log::info('Contract signature found and will be used for PDF', [
                    'id'          => $id,
                    'user_id'     => $consultantUser->id,
                    'contract_id' => $contractSignature->contract_id
                ]);

                $pdfContent = $this->contractPdfService->generateContract($consultantUser, $contractSignature);
                return response($pdfContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="contract.pdf"');
            } else {
                Log::warning('Attempt to view contract without valid signature', ['id' => $id]);
                return response()->view('errors.404', ['message' => 'No valid contract signature found for this contract.'], 404);
            }
        } catch (ModelNotFoundException $e) {
            Log::error('Contract not found', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->view('errors.404', ['message' => 'Contract not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error in viewContract', [
                'id'    => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->view('errors.500', [
                'message' => 'An error occurred while generating the contract. Please try again later.'
            ], 500);
        }
    }

    public function create()
    {
        $roles    = Role::all();
        $counties = County::all();
        return view('contracts.create', compact('roles', 'counties'));
    }

    public function terminate(Request $request, $id)
    {
        $request->validate([
            'termination_reason' => 'required|string',
            'termination_date'   => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $contractSignature = UserContractSignature::findOrFail($id);

            if (!in_array($contractSignature->status, ['approved', 'accepted'])) {
                return redirect()->back()->with('error', 'Only approved or accepted contracts can be terminated.');
            }

            $contractSignature->update([
                'status'             => 'terminated',
                'termination_reason' => $request->termination_reason,
                'termination_date'   => $request->termination_date,
                'terminated_at'      => now(),
                'terminated_by'      => auth()->id()
            ]);

            $draftContract = UserContractSignature::where('user_id', $contractSignature->user_id)
                ->where('status', 'draft')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($draftContract) {
                $draftContract->update([
                    'status'          => 'approved',
                    'activation_date' => now()
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Contract terminated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Contract termination failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to terminate contract: ' . $e->getMessage());
        }
    }

    public function edit(AdminContract $contract)
    {
        $roles         = Role::all();
        $counties      = County::all();
        $users         = User::all();
        $projects      = Projects::orderBy('name')->get();
        $durationTypes = ['days', 'months', 'years'];

        $contract->load('counties');

        return view('contracts.edit', compact('contract', 'roles', 'counties', 'users', 'projects', 'durationTypes'));
    }

    public function destroy(AdminContract $contract)
    {
        $contract->counties()->detach();
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contract deleted successfully.');
    }

    public function userContract()
    {
        $user            = Auth::user();
        $activeProjectId = $user->getActiveProjectId();

        if ($activeProjectId) {
            if ($user->role_id == 2) {
                $assignedCountyIds = DB::table('regional_coordinator_counties')
                    ->where('user_id', $user->id)
                    ->pluck('county_id');

                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->where(function ($q) use ($activeProjectId) {
                        $q->whereNull('project_id')
                          ->orWhere('project_id', $activeProjectId);
                    })
                    ->whereHas('counties', function ($query) use ($assignedCountyIds) {
                        $query->whereIn('county_id', $assignedCountyIds);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();
            } else {
                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->where(function ($q) use ($activeProjectId) {
                        $q->whereNull('project_id')
                          ->orWhere('project_id', $activeProjectId);
                    })
                    ->whereHas('counties', function ($query) use ($user) {
                        $query->where('county_id', $user->county_id);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();
            }
        } else {
            if ($user->role_id == 2) {
                $assignedCountyIds = DB::table('regional_coordinator_counties')
                    ->where('user_id', $user->id)
                    ->pluck('county_id');

                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->whereHas('counties', function ($query) use ($assignedCountyIds) {
                        $query->whereIn('county_id', $assignedCountyIds);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();
            } else {
                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->whereHas('counties', function ($query) use ($user) {
                        $query->where('county_id', $user->county_id);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();
            }
        }

        return view('onboarding.contract', compact('contract'));
    }

    private function storeBase64Image($base64Image)
    {
        $image_parts    = explode(";base64,", $base64Image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type     = $image_type_aux[1];
        $image_base64   = base64_decode($image_parts[1]);
        $file_name      = 'authority_signatures/' . uniqid() . '.' . $image_type;

        Storage::disk('public')->put($file_name, $image_base64);

        return $file_name;
    }

    public function viewUserContract()
    {
        $user            = Auth::user();
        $activeProjectId = $user->getActiveProjectId();
        $contract        = null;

        if ($activeProjectId) {
            if ($user->role_id == 2) {
                // Regional Coordinator: look up by assigned counties
                $assignedCountyIds = DB::table('regional_coordinator_counties')
                    ->where('user_id', $user->id)
                    ->pluck('county_id');

                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->where(function ($q) use ($activeProjectId) {
                        $q->whereNull('project_id')
                          ->orWhere('project_id', $activeProjectId);
                    })
                    ->whereHas('counties', function ($query) use ($assignedCountyIds) {
                        $query->whereIn('county_id', $assignedCountyIds);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();

                Log::info('Group contract query result for RC (viewUserContract)', [
                    'user_id'           => $user->id,
                    'assigned_counties' => $assignedCountyIds,
                    'active_project_id' => $activeProjectId,
                    'contract_found'    => $contract ? $contract->id : null,
                ]);
            } else {
                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->where(function ($q) use ($activeProjectId) {
                        $q->whereNull('project_id')
                          ->orWhere('project_id', $activeProjectId);
                    })
                    ->whereHas('counties', function ($query) use ($user) {
                        $query->where('county_id', $user->county_id);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();
            }
        } else {
            // No active project — fall back to any matching published contract
            if ($user->role_id == 2) {
                $assignedCountyIds = DB::table('regional_coordinator_counties')
                    ->where('user_id', $user->id)
                    ->pluck('county_id');

                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->whereHas('counties', function ($query) use ($assignedCountyIds) {
                        $query->whereIn('county_id', $assignedCountyIds);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();

                Log::info('Group contract query result for RC (viewUserContract, no project)', [
                    'user_id'           => $user->id,
                    'assigned_counties' => $assignedCountyIds,
                    'contract_found'    => $contract ? $contract->id : null,
                ]);
            } else {
                $contract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->whereHas('counties', function ($query) use ($user) {
                        $query->where('county_id', $user->county_id);
                    })
                    ->with('counties')
                    ->latest()
                    ->first();
            }
        }

        if (!$contract) {
            return response()->view('errors.404', [
                'message' => 'No contract found'
            ], 404);
        }

        // Get the user's signed contract signature
        $contractSignature = UserContractSignature::where('contract_id', $contract->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$contractSignature || !$contractSignature->signature) {
            return response()->view('errors.404', [
                'message' => 'No signed contract found'
            ], 404);
        }

        // Generate and return the PDF
        $pdfContent = $this->contractPdfService->generateContract($user, $contractSignature);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="contract.pdf"');
    }

    private function isContractExpired($contract)
    {
        if (!$contract->start_date) {
            return false;
        }

        $startDate = Carbon::parse($contract->start_date);
        $endDate   = $this->getEndDate($startDate, $contract->number_of_days);
        return Carbon::now()->isAfter($endDate);
    }

    public function reset(Request $request, $id)
{
    $request->validate([
        'reset_reason' => 'required|string'
    ]);

    try {
        DB::beginTransaction();

        $contractSignature = UserContractSignature::findOrFail($id);
        
        $user = $contractSignature->user;

        Log::info("Resetting user contract status", [
            'user_id'     => $user->id,
            'contract_id' => $id,
            'old_status'  => $contractSignature->status,
            'reason'      => $request->reset_reason,
            'reset_by'    => auth()->id()
        ]);

        $user->update([
            'onboarding_status' => false,
            'contract_signed'   => false,
        ]);

        $contractSignature->delete();

        DB::commit();

        return redirect()->back()->with('success', 'User has been reset successfully and can now start a new onboarding process.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('User reset failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to reset user. Please try again.');
    }
}

    public function handleExpiredContracts()
    {
        $expiredSignatures = UserContractSignature::with(['contract', 'user'])
            ->whereIn('status', ['approved', 'accepted'])
            ->get()
            ->filter(function ($signature) {
                return $this->isContractExpired($signature->contract);
            });

        foreach ($expiredSignatures as $signature) {
            $signature->update([
                'status'            => 'expired',
                'completion_date'   => Carbon::now(),
                'completion_reason' => 'Contract period expired',
                'completion_notes'  => 'Automatically marked as expired by system'
            ]);

            $activeContracts = UserContractSignature::where('user_id', $signature->user_id)
                ->whereIn('status', ['approved', 'accepted'])
                ->count();

            if ($activeContracts === 0) {
                $signature->user->update(['available_for_transfer' => true]);
            }
        }

        return $expiredSignatures->count() . " expired contracts processed.";
    }

    public function checkTransferEligibility(User $user)
    {
        $activeContracts         = UserContractSignature::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'accepted'])
            ->get();

        $hasActiveValidContracts = false;

        foreach ($activeContracts as $contract) {
            if (!$this->isContractExpired($contract->contract)) {
                $hasActiveValidContracts = true;
                break;
            }
        }

        return !$hasActiveValidContracts;
    }

    private function checkAndHandleExpiredContracts()
    {
        $expiredContracts = AdminContract::with('counties')
            ->where('active_for_onboarding', true)
            ->get()
            ->filter(function ($contract) {
                return $this->isContractExpired($contract);
            });

        foreach ($expiredContracts as $contract) {
            $contract->update([
                'active_for_onboarding' => false,
                'status'                => 'dropped'
            ]);

            UserContractSignature::where('contract_id', $contract->id)
                ->whereIn('status', ['approved', 'accepted'])
                ->update([
                    'status'            => 'expired',
                    'completion_date'   => now(),
                    'completion_reason' => 'Contract expired',
                    'completion_notes'  => 'Automatically marked as expired by system'
                ]);
        }
    }

    private function validateContract(Request $request)
    {
        return $request->validate([
            'title'                => 'required|string|max:255',
            'start_date'           => ['required', 'date', 'after_or_equal:today'],
            'number_of_days'       => 'required|integer|min:1',
            'description'          => 'required|string',
            'role_id'              => 'required|exists:roles,id',
            'counties'             => 'required|array',
            'counties.*'           => 'exists:counties,id',
            'status'               => [
                'required',
                'in:draft,published,dropped',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === 'published' && !$request->has('active_for_onboarding')) {
                        $fail('Published contracts must be active for onboarding.');
                    }
                }
            ],
            'authority_signature'   => 'required|string',
            'active_for_onboarding' => 'boolean'
        ]);
    }

    private function hasActiveContract($roleId, $countyIds)
    {
        return AdminContract::where('role_id', $roleId)
            ->where('active_for_onboarding', true)
            ->where('status', 'published')
            ->whereHas('counties', function ($query) use ($countyIds) {
                $query->whereIn('county_id', $countyIds);
            })
            ->whereDate('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', now());
            })
            ->exists();
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $contract = AdminContract::withTrashed()->findOrFail($id);

            if ($contract->active_for_onboarding) {
                $hasConflicts = $this->hasActiveContract(
                    $contract->role_id,
                    $contract->counties->pluck('id')->toArray()
                );

                if ($hasConflicts) {
                    return back()->with('error', 'Cannot restore contract. Active contract exists for this role and counties.');
                }
            }

            $contract->restore();
            DB::commit();

            return back()->with('success', 'Contract restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contract restoration failed', [
                'contract_id' => $id,
                'error'       => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to restore contract.');
        }
    }

    public function auditTrail($contract)
    {
        try {
            if (!($contract instanceof AdminContract)) {
                $contract = AdminContract::findOrFail($contract);
            }

            $contract->load(['versions' => function ($query) {
                $query->with('changedByUser')
                      ->orderBy('created_at', 'desc');
            }]);

            return view('contracts.audit-trail', [
                'contract' => $contract,
                'versions' => $contract->versions,
                'message'  => $contract->versions->count() ? null : 'No changes have been recorded for this contract yet.'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to load audit trail. Please try again.');
        }
    }

    public function previewVersion(AdminContract $contract, $versionId)
    {
        try {
            $version = ContractVersion::findOrFail($versionId);

            if ($version->contract_id !== $contract->id) {
                throw new ModelNotFoundException();
            }

            $tempContract = new AdminContract();
            $tempContract->fill([
                'description'          => $version->description,
                'authority_signature'  => $version->authority_signature,
                'authority_name'       => $version->authority_name,
                'authority_designation'=> $version->authority_designation,
                'status'               => $version->status,
            ]);

            $pdfContent = $this->contractPdfService->generateVersionContract($tempContract, $version);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="contract_version_' . $versionId . '.pdf"');
        } catch (ModelNotFoundException $e) {
            return response()->view('errors.404', ['message' => 'Contract version not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error previewing contract version', [
                'contract_id' => $contract->id,
                'version_id'  => $versionId,
                'error'       => $e->getMessage()
            ]);
            return response()->view('errors.500', ['message' => 'Error generating contract preview'], 500);
        }
    }

    public function downloadVersion(AdminContract $contract, $versionId)
    {
        try {
            $version = ContractVersion::findOrFail($versionId);

            if ($version->contract_id !== $contract->id) {
                throw new ModelNotFoundException();
            }

            $tempContract = new AdminContract();
            $tempContract->fill([
                'description'          => $version->description,
                'authority_signature'  => $version->authority_signature,
                'authority_name'       => $version->authority_name,
                'authority_designation'=> $version->authority_designation,
                'status'               => $version->status,
            ]);

            $pdfContent = $this->contractPdfService->generateVersionContract($tempContract, $version);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="contract_version_' . $versionId . '.pdf"');
        } catch (ModelNotFoundException $e) {
            return response()->view('errors.404', ['message' => 'Contract version not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error downloading contract version', [
                'contract_id' => $contract->id,
                'version_id'  => $versionId,
                'error'       => $e->getMessage()
            ]);
            return response()->view('errors.500', ['message' => 'Error generating contract download'], 500);
        }
    }

    private function calculateWorkingDays($startDate, $numberOfDays)
    {
        $currentDate = Carbon::parse($startDate);
        $workingDays = 0;
        $daysAdded   = 0;

        while ($daysAdded < $numberOfDays) {
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
                $daysAdded++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    private function getEndDate($startDate, $numberOfDays)
    {
        $currentDate = Carbon::parse($startDate);
        $daysAdded   = 0;

        while ($daysAdded < $numberOfDays) {
            $currentDate->addDay();
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $daysAdded++;
            }
        }

        return $currentDate->format('Y-m-d');
    }

    public function searchUsers(Request $request, AdminContract $contract)
{
    $search = $request->get('q');

    $users = User::where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        })
        ->orderBy('first_name')
        ->limit(20)
        ->get(['id', 'first_name', 'last_name']);

    return response()->json(
        $users->map(function ($user) {
            return [
                'id'   => $user->id,
                'text' => "{$user->first_name} {$user->last_name}"
            ];
        })
    );
}

}