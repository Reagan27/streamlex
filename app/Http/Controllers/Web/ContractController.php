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

class ContractController extends Controller
{

    protected $contractPdfService;

    public function __construct(ContractPdfService $contractPdfService)
    {
        $this->contractPdfService = $contractPdfService;
    }

    public function index(Request $request)
    {
        // Start with the base query
        $query = AdminContract::with('counties');
    
        // Apply search filter if search parameter is provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('counties', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        // Get the contracts and calculate remaining days
        $contracts = $query->get()->map(function ($contract) {
            $contract->remaining_days = $this->calculateRemainingDays($contract);
            // Ensure start_date is a Carbon instance
            $contract->start_date = $contract->start_date ? Carbon::parse($contract->start_date) : null;
            return $contract;
        });
    
        return view('contracts.index', compact('contracts'));
    }

    
    // public function update(Request $request, AdminContract $contract)
    // {
    //     $validatedData = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'start_date' => 'required|date',
    //         'number_of_days' => 'required|integer',
    //         'description' => 'required|string',
    //         'role_id' => 'required|exists:roles,id',
    //         'counties' => 'required|array',
    //         'counties.*' => 'exists:counties,id',
    //         'status' => 'required|in:draft,published,dropped',
    //         'authority_name' => 'required|string|max:255',
    //         'authority_designation' => 'required|string|max:255',
    //         'authority_signature' => 'required|string',
    //         'change_reason' => 'required|string',
    //     ]);
    
    //     try {
    //         DB::beginTransaction();
    
    //         // Save previous state for version tracking
    //         $oldState = $contract->toArray();
    
    //         // Update basic contract info
    //         $contract->title = $validatedData['title'];
    //         $contract->start_date = $validatedData['start_date'];
    //         $contract->number_of_days = $validatedData['number_of_days'];
    //         $contract->description = $validatedData['description'];
    //         $contract->role_id = $validatedData['role_id'];
    //         $contract->status = $validatedData['status'];
    //         $contract->authority_name = $validatedData['authority_name'];
    //         $contract->authority_designation = $validatedData['authority_designation'];
    //         $contract->authority_signature = $validatedData['authority_signature'];
    //         $contract->active_for_onboarding = $request->has('active_for_onboarding');
            
    //         $contract->save();
    
    //         // Create version record
    //         ContractVersion::create([
    //             'contract_id' => $contract->id,
    //             'status' => $contract->status,
    //             'description' => $contract->description,
    //             'authority_signature' => $contract->authority_signature,
    //             'authority_name' => $contract->authority_name,
    //             'authority_designation' => $contract->authority_designation,
    //             'change_reason' => $validatedData['change_reason'],
    //             'changed_by' => auth()->id()
    //         ]);
    
    //         // Sync counties
    //         $contract->counties()->sync($request->input('counties', []));
    
    //         DB::commit();
    //         return redirect()->route('contracts.index')
    //             ->with('success', 'Contract updated successfully.');
    
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Contract update failed', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return back()->withInput()
    //             ->with('error', 'Failed to update contract: ' . $e->getMessage());
    //     }
    // }


    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'start_date' => 'required|date',
    //         'number_of_days' => 'required|integer',
    //         'description' => 'required|string',
    //         'role_id' => 'required|exists:roles,id',
    //         'counties' => 'required|array',
    //         'counties.*' => 'exists:counties,id',
    //         'status' => 'required|in:draft,published,dropped',
    //         'authority_name' => 'required|string|max:255',
    //         'authority_designation' => 'required|string|max:255',
    //         'authority_signature' => 'required|string',
    //     ]);
    
    //     try {
    //         DB::beginTransaction();
    
    //         // Create contract
    //         $contract = AdminContract::create([
    //             'title' => $validatedData['title'],
    //             'start_date' => $validatedData['start_date'],
    //             'number_of_days' => $validatedData['number_of_days'],
    //             'description' => $validatedData['description'],
    //             'role_id' => $validatedData['role_id'],
    //             'status' => $validatedData['status'],
    //             'authority_name' => $validatedData['authority_name'],
    //             'authority_designation' => $validatedData['authority_designation'],
    //             'authority_signature' => $validatedData['authority_signature'],
    //             'active_for_onboarding' => $request->has('active_for_onboarding')
    //         ]);
    
    //         // Create initial version
    //         ContractVersion::create([
    //             'contract_id' => $contract->id,
    //             'status' => $contract->status,
    //             'description' => $contract->description,
    //             'authority_signature' => $contract->authority_signature,
    //             'authority_name' => $contract->authority_name,
    //             'authority_designation' => $contract->authority_designation,
    //             'change_reason' => 'Initial contract creation',
    //             'changed_by' => auth()->id()
    //         ]);
    
    //         // Sync counties
    //         $contract->counties()->sync($request->input('counties', []));
    
    //         DB::commit();
    //         return redirect()->route('contracts.index')
    //             ->with('success', 'Contract created successfully.');
    
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Contract creation failed', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return back()->withInput()
    //             ->with('error', 'Failed to create contract: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => 'required|date',
        'number_of_days' => 'required|integer',
        'description' => 'required|string',
        'role_id' => 'required|exists:roles,id',
        'counties' => 'required|array',
        'counties.*' => 'exists:counties,id',
        'status' => 'required|in:draft,published,dropped',
        'authority_name' => 'required|string|max:255',
        'authority_designation' => 'required|string|max:255',
        'authority_signature' => 'required|string',
    ]);

    try {
        DB::beginTransaction();

        // Calculate actual end date accounting for Sundays
        $endDate = $this->getEndDate($validatedData['start_date'], $validatedData['number_of_days']);
        
        $contract = AdminContract::create([
            'title' => $validatedData['title'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $endDate,
            'number_of_days' => $validatedData['number_of_days'], // This now represents working days
            'description' => $validatedData['description'],
            'role_id' => $validatedData['role_id'],
            'status' => $validatedData['status'],
            'authority_name' => $validatedData['authority_name'],
            'authority_designation' => $validatedData['authority_designation'],
            'authority_signature' => $validatedData['authority_signature'],
            'active_for_onboarding' => $request->has('active_for_onboarding')
        ]);

        ContractVersion::create([
            'contract_id' => $contract->id,
            'status' => $contract->status,
            'description' => $contract->description,
            'authority_signature' => $contract->authority_signature,
            'authority_name' => $contract->authority_name,
            'authority_designation' => $contract->authority_designation,
            'change_reason' => 'Initial contract creation',
            'changed_by' => auth()->id()
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

public function update(Request $request, AdminContract $contract)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => 'required|date',
        'number_of_days' => 'required|integer',
        'description' => 'required|string',
        'role_id' => 'required|exists:roles,id',
        'counties' => 'required|array',
        'counties.*' => 'exists:counties,id',
        'status' => 'required|in:draft,published,dropped',
        'authority_name' => 'required|string|max:255',
        'authority_designation' => 'required|string|max:255',
        'authority_signature' => 'required|string',
        'change_reason' => 'required|string',
    ]);

    try {
        DB::beginTransaction();

        $oldState = $contract->toArray();
        
        // Calculate new end date accounting for Sundays
        $endDate = $this->getEndDate($validatedData['start_date'], $validatedData['number_of_days']);

        $contract->title = $validatedData['title'];
        $contract->start_date = $validatedData['start_date'];
        $contract->end_date = $endDate;
        $contract->number_of_days = $validatedData['number_of_days'];
        $contract->description = $validatedData['description'];
        $contract->role_id = $validatedData['role_id'];
        $contract->status = $validatedData['status'];
        $contract->authority_name = $validatedData['authority_name'];
        $contract->authority_designation = $validatedData['authority_designation'];
        $contract->authority_signature = $validatedData['authority_signature'];
        $contract->active_for_onboarding = $request->has('active_for_onboarding');
        
        $contract->save();

        ContractVersion::create([
            'contract_id' => $contract->id,
            'status' => $contract->status,
            'description' => $contract->description,
            'authority_signature' => $contract->authority_signature,
            'authority_name' => $contract->authority_name,
            'authority_designation' => $contract->authority_designation,
            'change_reason' => $validatedData['change_reason'],
            'changed_by' => auth()->id()
        ]);

        $contract->counties()->sync($request->input('counties', []));

        DB::commit();
        return redirect()->route('contracts.index')
            ->with('success', 'Contract updated successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Contract update failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->withInput()
            ->with('error', 'Failed to update contract: ' . $e->getMessage());
    }
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
                'status' => 'inactive',
                'completion_reason' => 'Contract no longer exists',
                'completion_date' => now()
            ]);
            
            return [
                'active' => false,
                'reason' => 'contract_not_found'
            ];
        }
        if (in_array($currentContract->status, ['terminated', 'expired', 'inactive'])) {
            return [
                'active' => false,
                'reason' => $currentContract->status
            ];
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
    $endDate = $this->getEndDate($startDate, $contract->number_of_days);
    $today = Carbon::now();

    if ($today > $endDate) {
        return 'Expired';
    }

    // Count working days between today and end date
    $remainingDays = 0;
    $currentDate = Carbon::now();
    
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
        $contracts = AdminContract::all();
        $activeContracts = 0;
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
            'activeContracts' => $activeContracts,
            'expiredContracts' => $expiredContracts,
        ];
    }

    public function contractsList(Request $request)
    {
        $currentUser = auth()->user();
        
        // Initialize the query with all necessary relationships
        $query = UserContractSignature::with([
            'user.role', 
            'user.county',
            'contract'
        ])->whereIn('status', ['approved', 'accepted', 'declined', 'terminated', 'inactive']);
    
        // Apply role-based access control
        if (!$currentUser->hasRole(['Admin', 'Manager'])) {
            $this->applyRoleBasedFilters($query, $currentUser);
        }
    
        // Apply search filters
        if ($request->filled('search')) {
            $this->applySearchFilter($query, $request->input('search'));
        }
    
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
    
        // Apply county filter
        if ($request->filled('county')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('county_id', $request->input('county'));
            });
        }
    
        // Apply role filter
        if ($request->filled('role')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('role_id', $request->input('role'));
            });
        }
    
        // Apply date range filter
        if ($request->filled('date_range')) {
            $this->applyDateRangeFilter($query, $request->input('date_range'));
        }
    
        // Get paginated results
        $contracts = $query->latest()->paginate(20)->withQueryString();
    
        // Get filter options
        $counties = County::orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();
    
        return view('contractsList.index', compact('contracts', 'counties', 'roles'));
    }
    
    /**
     * Apply role-based access filters to the query
     */
    private function applyRoleBasedFilters($query, $currentUser)
    {
        $roleHierarchyService = app(RoleHierarchyService::class);
        $subordinateRoles = $roleHierarchyService->getAllSubordinateRoles($currentUser->role->name);
    
        $query->whereHas('user', function ($q) use ($currentUser, $subordinateRoles) {
            $q->whereIn('role_id', function ($subQuery) use ($subordinateRoles) {
                $subQuery->select('id')
                    ->from('roles')
                    ->whereIn('name', $subordinateRoles);
            });
    
            // County-based filtering
            if ($currentUser->role->name === 'Regional_Coordinator') {
                $assignedCountyIds = $currentUser->counties->pluck('id')->toArray();
                $q->whereIn('county_id', $assignedCountyIds);
            } else {
                $q->where('county_id', $currentUser->county_id);
            }
        });
    }
    
    /**
     * Apply search filter to the query
     */
    private function applySearchFilter($query, $searchTerm)
    {
        $query->whereHas('user', function ($q) use ($searchTerm) {
            $q->where(function($inner) use ($searchTerm) {
                $inner->where('first_name', 'like', "%{$searchTerm}%")
                      ->orWhere('last_name', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        })->orWhereHas('contract', function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%");
        });
    }
    
    /**
     * Apply date range filter to the query
     */
    private function applyDateRangeFilter($query, $dateRange)
    {
        $now = now();
        
        switch ($dateRange) {
            case 'today':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    $now->startOfWeek()->toDateTimeString(),
                    $now->endOfWeek()->toDateTimeString()
                ]);
                break;
            case 'month':
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
            case 'year':
                $query->whereYear('created_at', $now->year);
                break;
        }
    }

    public function viewContract($id)
    {
        try {
            $contractSignature = UserContractSignature::findOrFail($id);
            Log::info('Contract signature found', [
                'id' => $id, 
                'user_id' => $contractSignature->user_id,
                'contract_id' => $contractSignature->contract_id
            ]);
    
            // Pass the contract signature to ensure correct contract is used
            $pdfContent = $this->contractPdfService->generateContract($contractSignature->user, $contractSignature);
    
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="contract.pdf"');
        } catch (ModelNotFoundException $e) {
            Log::error('Contract not found', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->view('errors.404', ['message' => 'Contract not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error in viewContract', [
                'id' => $id,
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
        $roles = Role::all();
        $counties = County::all();
        return view('contracts.create', compact('roles', 'counties'));
    }



    public function terminate(Request $request, $id)
    {
        $request->validate([
            'termination_reason' => 'required|string',
            'termination_date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            $contractSignature = UserContractSignature::findOrFail($id);
            
            if (!in_array($contractSignature->status, ['approved', 'accepted'])) {
                return redirect()->back()->with('error', 'Only approved or accepted contracts can be terminated.');
            }

            // Update the current contract status
            $contractSignature->update([
                'status' => 'terminated',
                'termination_reason' => $request->termination_reason,
                'termination_date' => $request->termination_date,
                'terminated_at' => now(),
                'terminated_by' => auth()->id()
            ]);

            // Check if user has any draft contracts that can be activated
            $draftContract = UserContractSignature::where('user_id', $contractSignature->user_id)
                ->where('status', 'draft')
                ->orderBy('created_at', 'asc')
                ->first();

            if ($draftContract) {
                $draftContract->update([
                    'status' => 'approved',
                    'activation_date' => now()
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Contract terminated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Contract termination failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to terminate contract. Please try again.');
        }
    }


    
    public function show(AdminContract $contract)
    {
        $contract->load('counties');
        return view('contracts.show', compact('contract'));
    }

    public function getInfo($id)
    {
        try {
            $contract = UserContractSignature::with([
                'user:id,first_name,last_name,phone,email,address,role_id',
                'user.role:id,display_name',
                'user.bankDetails:id,user_id,bank_id,bank_branch,account_name,account_number',
                'user.bankDetails.bank:id,name,bank_code',
                'user.userDocuments:id,user_id,id_number,id_photo_path,kra_pin,kra_certificate_path',
            ])->findOrFail($id);
    
            // Log the contract data for debugging
            \Log::info('Contract data:', ['contract' => $contract->toArray()]);
    
            $response = [
                'name' => $contract->user->first_name . ' ' . $contract->user->last_name,
                'phone' => $contract->user->phone,
                'email' => $contract->user->email,
                'address' => $contract->user->address,
                'role' => $contract->user->role->display_name,
                'bankDetails' => null,
                'documents' => null,
                'signature' => null,
            ];
    
            if ($contract->user->bankDetails) {
                $response['bankDetails'] = [
                    'bank' => $contract->user->bankDetails->bank->name ?? $contract->user->bankDetails->bank_id,
                    'branch' => $contract->user->bankDetails->bank_branch,
                    'bankCode' => $contract->user->bankDetails->bank->bank_code ?? 'N/A',
                    'accountName' => $contract->user->bankDetails->account_name,
                    'accountNumber' => $contract->user->bankDetails->account_number,
                ];
            }
    
            if ($contract->user->documents) {
                $response['documents'] = [
                    'idNumber' => $contract->user->documents->id_number,
                    'idPhotoUrl' => asset('storage/' . $contract->user->documents->id_photo_path),
                    'kraPin' => $contract->user->documents->kra_pin,
                    'certificateUrl' => asset('storage/' . $contract->user->documents->kra_certificate_path),
                ];
            }
    
            $response['signature'] = [
                'signedAt' => $contract->agreed_at,
                'status' => ucfirst($contract->status),
                'signatureUrl' => $contract->signature,
            ];
    
            return response()->json($response);
    
        } catch (ModelNotFoundException $e) {
            \Log::error('Contract not found: ' . $e->getMessage());
            return response()->json(['error' => 'Contract not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Error in getInfo: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while fetching contract information.'], 500);
        }
    }

    public function edit(AdminContract $contract)
    {
        $roles = Role::all();
        $counties = County::all();
        return view('contracts.edit', compact('contract', 'roles', 'counties'));
    }


    public function destroy(AdminContract $contract)
    {
        $contract->counties()->detach();
        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contract deleted successfully.');
    }


    public function userContract()
    {
        $user = Auth::user();
        $contract = AdminContract::where('role_id', $user->role_id)
            ->where('status', 'published')
            ->whereHas('counties', function ($query) use ($user) {
                $query->where('counties.id', $user->county_id);
            })
            ->with('counties')
            ->first();

        return view('onboarding.contract', compact('contract'));
    }

    private function storeBase64Image($base64Image)
    {
        $image_parts = explode(";base64,", $base64Image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file_name = 'authority_signatures/' . uniqid() . '.' . $image_type;
        
        Storage::disk('public')->put($file_name, $image_base64);

        return $file_name;
    }

    public function viewUserContract()
    {
        $user = Auth::user();
        
        // Get the specific contract signature for this user
        $contractSignature = UserContractSignature::where('user_id', $user->id)
            ->whereNotNull('signature')
            ->whereNotNull('agreed_at')
            ->orderBy('agreed_at', 'asc')
            ->first();

        if (!$contractSignature) {
            Log::error('No signed contract found for user', [
                'user_id' => $user->id
            ]);
            return response('No contract found for this user.', 404);
        }

        try {
            // Add debug logging
            Log::info('Found contract signature', [
                'user_id' => $user->id,
                'contract_signature_id' => $contractSignature->id,
                'contract_id' => $contractSignature->contract_id,
                'status' => $contractSignature->status,
                'agreed_at' => $contractSignature->agreed_at
            ]);

            // Pass the contract signature explicitly to ensure correct contract is used
            $pdfContent = $this->contractPdfService->generateContract($user, $contractSignature);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="your_contract.pdf"');
        } catch (\Exception $e) {
            Log::error('Error generating user contract PDF', [
                'user_id' => $user->id,
                'contract_signature_id' => $contractSignature->id,
                'contract_id' => $contractSignature->contract_id,
                'error' => $e->getMessage()
            ]);
            return response('Error generating contract PDF', 500);
        }
    }


    private function isContractExpired($contract)
    {
        if (!$contract->start_date) {
            return false;
        }
        
        $startDate = Carbon::parse($contract->start_date);
        $endDate = $this->getEndDate($startDate, $contract->number_of_days);
        return Carbon::now()->isAfter($endDate);
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
            // Mark contract as expired
            $signature->update([
                'status' => 'expired',
                'completion_date' => Carbon::now(),
                'completion_reason' => 'Contract period expired',
                'completion_notes' => 'Automatically marked as expired by system'
            ]);

            // Check if user has any other active contracts
            $activeContracts = UserContractSignature::where('user_id', $signature->user_id)
                ->whereIn('status', ['approved', 'accepted'])
                ->count();

            if ($activeContracts === 0) {
                $signature->user->update([
                    'available_for_transfer' => true
                ]);
            }
        }

        return $expiredSignatures->count() . " expired contracts processed.";
    }


    public function checkTransferEligibility(User $user)
    {
        $activeContracts = UserContractSignature::where('user_id', $user->id)
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

    public function transferUser(Request $request, User $user)
    {
        $request->validate([
            'new_county_id' => 'required|exists:counties,id',
            'new_contract_id' => 'required|exists:admin_contracts,id',
            'transfer_reason' => 'required|string',
            'transfer_date' => 'required|date',
            'transfer_type' => 'required|in:expired,completed,terminated'
        ]);

        // Check transfer eligibility
        if (!$this->checkTransferEligibility($user)) {
            return back()->with('error', 'User has active, non-expired contracts and cannot be transferred.');
        }

        try {
            DB::beginTransaction();

            // Mark all previous contracts as expired/completed if they're not already
            UserContractSignature::where('user_id', $user->id)
                ->whereIn('status', ['approved', 'accepted'])
                ->update([
                    'status' => 'expired',
                    'completion_date' => Carbon::now(),
                    'completion_reason' => 'Contract marked as expired due to transfer',
                    'completion_notes' => 'User transferred to new county'
                ]);

            // Create new contract signature for the transfer
            UserContractSignature::create([
                'user_id' => $user->id,
                'contract_id' => $request->new_contract_id,
                'status' => 'draft',
                'transfer_from_county' => $user->county_id,
                'transfer_reason' => $request->transfer_reason,
                'transfer_date' => $request->transfer_date,
                'transfer_type' => $request->transfer_type
            ]);

            // Update user's county
            $user->update([
                'county_id' => $request->new_county_id,
                'available_for_transfer' => false
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'User transferred successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }

    public function getUserContractStatus(User $user)
    {
        $contracts = UserContractSignature::with('contract')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($signature) {
                $contract = $signature->contract;
                $isExpired = $this->isContractExpired($contract);
                
                return [
                    'contract_id' => $contract->id,
                    'title' => $contract->title,
                    'status' => $signature->status,
                    'is_expired' => $isExpired,
                    'start_date' => $contract->start_date,
                    'end_date' => Carbon::parse($contract->start_date)->addDays($contract->number_of_days),
                    'days_remaining' => $isExpired ? 0 : Carbon::now()->diffInDays(
                        Carbon::parse($contract->start_date)->addDays($contract->number_of_days),
                        false
                    )
                ];
            });

        return [
            'contracts' => $contracts,
            'can_be_transferred' => $this->checkTransferEligibility($user),
            'active_contracts' => $contracts->where('is_expired', false)->count(),
            'expired_contracts' => $contracts->where('is_expired', true)->count()
        ];
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
            'status' => 'dropped'
        ]);

        // Mark all related user contracts as expired
        UserContractSignature::where('contract_id', $contract->id)
            ->whereIn('status', ['approved', 'accepted'])
            ->update([
                'status' => 'expired',
                'completion_date' => now(),
                'completion_reason' => 'Contract expired',
                'completion_notes' => 'Automatically marked as expired by system'
            ]);
    }
}

private function validateContract(Request $request) 
{
    return $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => [
            'required',
            'date',
            'after_or_equal:today' // Ensure contract doesn't start in the past
        ],
        'number_of_days' => 'required|integer|min:1',
        'description' => 'required|string',
        'role_id' => 'required|exists:roles,id',
        'counties' => 'required|array',
        'counties.*' => 'exists:counties,id',
        'status' => [
            'required',
            'in:draft,published,dropped',
            function ($attribute, $value, $fail) use ($request) {
                if ($value === 'published' && !$request->has('active_for_onboarding')) {
                    $fail('Published contracts must be active for onboarding.');
                }
            }
        ],
        'authority_signature' => 'required|string',
        'active_for_onboarding' => 'boolean'
    ]);
}

private function hasActiveContract($roleId, $countyIds) 
{
    return AdminContract::where('role_id', $roleId)
        ->where('active_for_onboarding', true)
        ->where('status', 'published')
        ->whereHas('counties', function($query) use ($countyIds) {
            $query->whereIn('county_id', $countyIds);
        })
        ->whereDate('start_date', '<=', now())
        ->where(function($query) {
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
        
        // Check if restoration would create conflicts
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
            'error' => $e->getMessage()
        ]);
        return back()->with('error', 'Failed to restore contract.');
    }
}

public function reset(Request $request, $id)
{
    $request->validate([
        'reset_reason' => 'required|string'
    ]);

    try {
        DB::beginTransaction();

        $contractSignature = UserContractSignature::findOrFail($id);
        
        // Get the user
        $user = $contractSignature->user;

        // Log the reset
        Log::info("Resetting user contract status", [
            'user_id' => $user->id,
            'contract_id' => $id,
            'old_status' => $contractSignature->status,
            'reason' => $request->reset_reason,
            'reset_by' => auth()->id()
        ]);

        // Reset user status
        $user->update([
            'onboarding_status' => false,
            'contract_signed' => false,
           
        ]);

        // Delete the contract signature record
        $contractSignature->delete();

        DB::commit();

        return redirect()->back()->with('success', 'User has been reset successfully and can now start a new onboarding process.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('User reset failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to reset user. Please try again.');
    }
}

public function batchOperation(Request $request)
{
    $request->validate([
        'contracts' => 'required|array',
        'contracts.*' => 'exists:admin_contracts,id',
        'action' => 'required|in:activate,deactivate,delete'
    ]);

    try {
        DB::beginTransaction();

        foreach ($request->contracts as $contractId) {
            $contract = AdminContract::findOrFail($contractId);
            
            switch ($request->action) {
                case 'activate':
                    if (!$this->hasActiveContract($contract->role_id, $contract->counties->pluck('id')->toArray())) {
                        $contract->update(['active_for_onboarding' => true]);
                    }
                    break;
                    
                case 'deactivate':
                    $contract->update(['active_for_onboarding' => false]);
                    break;

                case 'delete':
                    if (!$contract->userSignatures()->whereIn('status', ['approved', 'accepted'])->exists()) {
                        $contract->delete();
                    }
                    break;
            }
        }

        DB::commit();
        return back()->with('success', 'Batch operation completed successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Batch operation failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to process batch operation.');
    }
}

public function handle()
{
    $controller = app(ContractController::class);
    $controller->checkAndHandleExpiredContracts();
}

protected function schedule(Schedule $schedule)
{
    $schedule->command('contracts:check-expired')->daily();
}

public function auditTrail($contract)
{
    try {
        if (!($contract instanceof AdminContract)) {
            $contract = AdminContract::findOrFail($contract);
        }
        $contract->load(['versions' => function($query) {
            $query->with('changedByUser')
                  ->orderBy('created_at', 'desc');
        }]);

        return view('contracts.audit-trail', [
            'contract' => $contract,
            'versions' => $contract->versions,
            'message' => $contract->versions->count() ? null : 'No changes have been recorded for this contract yet.'
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

        // Create a temporary contract object with version data
        $tempContract = new AdminContract();
        $tempContract->fill([
            'description' => $version->description,
            'authority_signature' => $version->authority_signature,
            'authority_name' => $version->authority_name,
            'authority_designation' => $version->authority_designation,
            'status' => $version->status,
        ]);

        // Generate PDF using the version data
        $pdfContent = $this->contractPdfService->generateVersionContract($tempContract, $version);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="contract_version_' . $versionId . '.pdf"');

    } catch (ModelNotFoundException $e) {
        return response()->view('errors.404', ['message' => 'Contract version not found'], 404);
    } catch (\Exception $e) {
        Log::error('Error previewing contract version', [
            'contract_id' => $contract->id,
            'version_id' => $versionId,
            'error' => $e->getMessage()
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

        // Create a temporary contract object with version data
        $tempContract = new AdminContract();
        $tempContract->fill([
            'description' => $version->description,
            'authority_signature' => $version->authority_signature,
            'authority_name' => $version->authority_name,
            'authority_designation' => $version->authority_designation,
            'status' => $version->status,
        ]);

        // Generate PDF using the version data
        $pdfContent = $this->contractPdfService->generateVersionContract($tempContract, $version);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="contract_version_' . $versionId . '.pdf"');

    } catch (ModelNotFoundException $e) {
        return response()->view('errors.404', ['message' => 'Contract version not found'], 404);
    } catch (\Exception $e) {
        Log::error('Error downloading contract version', [
            'contract_id' => $contract->id,
            'version_id' => $versionId,
            'error' => $e->getMessage()
        ]);
        return response()->view('errors.500', ['message' => 'Error generating contract download'], 500);
    }
}


private function calculateWorkingDays($startDate, $numberOfDays) 
{
    $currentDate = Carbon::parse($startDate);
    $workingDays = 0;
    $daysAdded = 0;

    while ($daysAdded < $numberOfDays) {
        // Skip if it's a Sunday (Carbon uses 0 for Sunday)
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
    $daysAdded = 0;

    while ($daysAdded < $numberOfDays) {
        $currentDate->addDay();
        if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
            $daysAdded++;
        }
    }

    return $currentDate;
}
}
