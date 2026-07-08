<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanguard\User;
use Vanguard\UserBankDetail;
use Vanguard\UserDocument;
use Vanguard\UserContractSignature;
use Vanguard\Bank;
use Vanguard\AdminContract;
use Vanguard\BankBranch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Vanguard\Http\Controllers\Controller;

class OnboardingController extends Controller
{
    /**
     * Store or update user's banking details during onboarding.
     */
    private function storeBankingDetails(Request $request)
    {
        $user = auth()->user();
        $validatedData = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'bank_branch_code' => 'required|string',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
        ]);

        // Find the selected branch (optional: validate branch belongs to bank)
        $bankBranch = \Vanguard\BankBranch::where('branch_code', $validatedData['bank_branch_code'])
            ->where('bank_id', $validatedData['bank_id'])
            ->first();

        if (!$bankBranch) {
            return back()->withErrors(['bank_branch_code' => 'Invalid bank branch selected.']);
        }

        // Save or update user's bank details
        $user->bankDetails()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bank_id' => $validatedData['bank_id'],
                'bank_branch' => $bankBranch->branch_code,
                'account_name' => $validatedData['account_name'],
                'account_number' => $validatedData['account_number'],
            ]
        );

        $user->update(['banking_submitted' => true]);
    }
    protected $steps = [
        'welcome',
        'personal-info',
        'banking-details',
        'documents',
        'policy-agreement',
        'contract',
        'final-confirmation'
    ];

    private function getContractForUser($user)
    {
        Log::info("User ID: " . $user->id);
        Log::info("User Role ID: " . $user->role_id);

        // If user contract_type is 'individual', prioritize individual contract
        if ($user->contract_type === 'individual') {
            $individualContract = AdminContract::where('user_id', $user->id)
                ->where('contract_category', 'individual')
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->latest()
                ->first();
            if ($individualContract) {
                Log::info("Individual contract found: ID {$individualContract->id}");
                // Attach a flag to skip county checks
                $individualContract->skip_county_checks = true;
                return $individualContract;
            }
            // If not found, fall back to group contract logic
        }
        // If contract_type is group or fallback, proceed as before

        // Existing group contract logic
        if ($user->role_id == 2) { // Regional Coordinator
            Log::info("User is a Regional Coordinator");
            $assignedCountyIds = \DB::table('regional_coordinator_counties')
                ->where('user_id', $user->id)
                ->pluck('county_id');
            Log::info("Assigned County IDs: " . $assignedCountyIds->implode(', '));
            if ($assignedCountyIds->isEmpty()) {
                Log::warning("Regional Coordinator has no assigned counties");
                return null;
            }
            $activeProjectId = $user->getActiveProjectId();
            $contract = AdminContract::where('role_id', $user->role_id)
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->where(function($q) use ($activeProjectId) {
                    $q->whereNull('project_id')
                      ->orWhere('project_id', $activeProjectId);
                })
                ->whereHas('counties', function ($query) use ($assignedCountyIds) {
                    $query->whereIn('county_id', $assignedCountyIds);
                })
                ->latest()
                ->first();
            Log::info("Contract found: " . ($contract ? "Yes, ID: {$contract->id}" : "No"));
            return $contract;
        } else {
            if (!$user->county_id) {
                Log::warning('[Onboarding] User has no county_id assigned', [
                    'user_id' => $user->id
                ]);
                return null;
            }

            $contract = AdminContract::where('role_id', $user->role_id)
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->whereHas('counties', function ($query) use ($user) {
                    $query->where('county_id', $user->county_id);
                })
                ->latest()
                ->first();

            Log::info('[Onboarding] Contract query result (other roles)', [
                'user_id' => $user->id,
                'contract_found' => $contract ? true : false,
                'contract_id' => $contract ? $contract->id : null
            ]);
            return $contract;
        }
    }
    
    public function index()
    {
        Log::info('[Onboarding] index called', [
            'user_id' => auth()->user()->id,
            'role_id' => auth()->user()->role_id,
            'county_id' => auth()->user()->county_id,
            'active_project_id' => auth()->user()->getActiveProjectId(),
        ]);
        $user = auth()->user();
        $contractSignature = $user->contractSignature;

        if ($user->onboarding_status && !$this->shouldRestartOnboarding($contractSignature)) {
            return redirect('/')->with('info', 'You have already completed the onboarding process.');
        }

        // Get contract based on role AND active project
        $contract = $this->getContractForUser($user);
        $skipCountyChecks = ($contract && property_exists($contract, 'skip_county_checks') && $contract->skip_county_checks) ? true : false;

        Log::info('Contract Check:', [
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'active_project' => $user->getActiveProjectId(),
            'contract_found' => ($contract ? 'Yes' : 'No'),
            'contract_active' => ($contract ? $contract->active_for_onboarding : 'N/A')
        ]);

        $hasContract = $contract !== null && $contract->active_for_onboarding;

        $prevContractStatus = null;
        if ($contractSignature) {
            $prevContractStatus = $contractSignature->status;
        }

        return view('onboarding.welcome', compact('contract', 'hasContract', 'prevContractStatus', 'skipCountyChecks'));
    }

    
    
    
    private function checkOnboardingEligibility(User $user): array
    {
        // 1. Check if user is active
        if (!$user->isActive()) {
            Log::warning('Onboarding blocked: User not active', ['user_id' => $user->id]);
            return [
                'eligible' => false,
                'message' => 'Your account is not active. Please contact your administrator.'
            ];
        }

        // 2. Check if user has an active project assigned
        $activeProjectId = $user->getActiveProjectId();
        if (!$activeProjectId) {
            Log::warning('Onboarding blocked: No active project', ['user_id' => $user->id]);
            return [
                'eligible' => false,
                'message' => 'No active project assigned. Please contact your administrator to assign you to a project.'
            ];
        }

        // 3. Check if there's an active contract for this role AND project
        $contract = AdminContract::where('role_id', $user->role_id)
            ->where('status', 'published')
            ->where('active_for_onboarding', true)
            ->where(function($q) use ($activeProjectId) {
                $q->whereNull('project_id')
                  ->orWhere('project_id', $activeProjectId);
            })
            ->first();

        if (!$contract) {
            Log::warning('Onboarding blocked: No matching contract', [
                'user_id' => $user->id,
                'role_id' => $user->role_id,
                'project_id' => $activeProjectId
            ]);
            return [
                'eligible' => false,
                'message' => 'No active contract found for your role in the current project. Please contact your administrator.'
            ];
        }

        // 4. Check if contract matches user's county (if applicable)
        // If user has county_id, check contract counties, otherwise allow onboarding
        if ($user->county_id) {
            $contractHasCounty = $contract->counties()
                ->where('county_id', $user->county_id)
                ->exists();
            if (!$contractHasCounty) {
                Log::warning('Onboarding: Contract does not cover user county, but allowing onboarding since project and contract exist', [
                    'user_id' => $user->id,
                    'user_county' => $user->county_id,
                    'contract_id' => $contract->id
                ]);
                // Allow onboarding to proceed
            }
        }

        Log::info('Onboarding eligibility check passed', [
            'user_id' => $user->id,
            'project_id' => $activeProjectId,
            'contract_id' => $contract->id
        ]);

        return ['eligible' => true];
    }

    private function shouldRestartOnboarding($contractSignature)
    {
        if (!$contractSignature) {
            return true;
        }
    
        return in_array($contractSignature->status, [
            'declined',
            'terminated',
            'expired',
            'inactive'
        ]);
    }

    public function storeStep(Request $request, $step)
    {
        $user = auth()->user();
    
        switch ($step) {
            case 'personal-info':
                $user->update($request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                    'phone' => 'required|string|max:20',
                ]));
                break;
            case 'banking-details':
                $this->storeBankingDetails($request);
                break;
            case 'documents':
                $this->storeDocuments($request);
                break;
            case 'policy-agreement':
                $user->update(['policy_agreed' => true]);
                break;
            case 'contract':
                $this->signContract($request);
                break;
            case 'final-confirmation':
                $user->update(['onboarding_status' => true]);
                return redirect('/')->with('success', 'Onboarding completed successfully!');
        }
    
        $nextStep = $this->steps[array_search($step, $this->steps) + 1] ?? 'final-confirmation';
        return redirect()->route("onboarding.navigate", $nextStep);
    }
    
    public function finalConfirmation()
    {
        $user = auth()->user()->load(['documents', 'bankDetails.bank', 'bankDetails.bankBranch']);
        return view('onboarding.final-confirmation', compact('user'));
    }

                        /**
                         * Handles navigation between onboarding steps.
                         * Route: onboarding.navigate
                         */
                       public function navigate(Request $request, $step)
{
    $user = auth()->user()->load(['documents', 'bankDetails']);

    $contractSignature = $user->contractSignature;
    if ($user->onboarding_status && !$this->shouldRestartOnboarding($contractSignature)) {
        return redirect('/')->with('info', 'You have already completed the onboarding process.');
    }

    if (!in_array($step, $this->steps)) {
        return redirect()->route('onboarding.navigate', 'welcome');
    }

    $currentStepIndex = array_search($step, $this->steps);
    $prevStep = $currentStepIndex > 0 ? $this->steps[$currentStepIndex - 1] : null;
    $nextStep = $currentStepIndex < count($this->steps) - 1 ? $this->steps[$currentStepIndex + 1] : null;

    $viewData = compact('user', 'prevStep', 'nextStep', 'step');

    if ($step === 'welcome') {
        $contract = $this->getContractForUser($user);
        $hasContract = $contract !== null && $contract->active_for_onboarding;
        $contractSignature = UserContractSignature::where('user_id', $user->id)
            ->latest()
            ->first();
        $prevContractStatus = $contractSignature ? $contractSignature->status : null;
        $viewData = array_merge($viewData, compact('contract', 'hasContract', 'prevContractStatus', 'contractSignature'));
    } elseif ($step === 'banking-details') {
        $viewData['banks'] = Bank::all();
    } elseif ($step === 'contract') {
        $viewData['contract'] = $this->getContractForUser($user);
    }

    return view("onboarding.{$step}", $viewData);
}
    public function completeFinalConfirmation()
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $activeProjectId = $user->getActiveProjectId();
            // Get the latest contract signature for the active project
            $contractSignature = UserContractSignature::where('user_id', $user->id)
                ->where('project_id', $activeProjectId)
                ->latest()
                ->first();

            if ($contractSignature && $contractSignature->signature) {
                // Update contract status to draft and keep signature
                $contractSignature->update([
                    'status' => 'draft',
                    'agreed_at' => now(),
                ]);
            } elseif ($contractSignature && !$contractSignature->signature) {
                // If signature is missing, fail gracefully
                throw new \Exception('No signature found for your contract. Please sign the contract before completing onboarding.');
            } else {
                // If no contract signature exists, fail gracefully
                throw new \Exception('No contract signature found. Please sign the contract before completing onboarding.');
            }

            // Update user onboarding status
            $user->update([
                'onboarding_status' => true,
                'contract_signed' => true,
                'documents_submitted' => true,
                'bank_details_submitted' => true
            ]);

            DB::commit();
            return redirect()->route('dashboard')
                ->with('success', 'Onboarding completed successfully! Please await contract approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to complete onboarding', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => $e->getMessage()]);
        }
        
        if (!$bankBranch) {
            return back()->withErrors(['bank_branch_code' => 'Invalid bank branch selected.']);
        }
        
        $user->bankDetails()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bank_id' => $validatedData['bank_id'],
                'bank_branch' => $bankBranch->branch_code,
                'account_name' => $validatedData['account_name'],
                'account_number' => $validatedData['account_number'],
            ]
        );
        
        $user->update(['banking_submitted' => true]);
    }

    private function storeDocuments(Request $request)
    {
        $maxFileSize = 32768; // 32MB
        
        $messages = [
            'id_photo.max' => 'ID Photo must not be larger than 32MB',
            'kra_certificate.max' => 'KRA Certificate must not be larger than 32MB',
        ];
        
        $data = $request->validate([
            'id_number' => ['required', 'regex:/^[0-9]{5,9}$/'],
            'kra_pin' => 'required|string|size:11',
            'id_photo' => $request->has('replace_id_photo') 
                ? "required|file|mimes:pdf,jpg,jpeg,png|max:$maxFileSize" 
                : 'nullable',
            'kra_certificate' => $request->has('replace_kra_certificate') 
                ? "required|file|mimes:pdf,jpg,jpeg,png|max:$maxFileSize" 
                : 'nullable',
            'shif_number' => 'nullable|string|max:32',
            'shif_document' => "nullable|file|mimes:pdf,jpg,jpeg,png|max:$maxFileSize",
            'nssf_number' => 'nullable|string|max:32',
            'nssf_document' => "nullable|file|mimes:pdf,jpg,jpeg,png|max:$maxFileSize",
        ], $messages);
    
        try {
            $user = auth()->user();
            
            if ($request->hasFile('id_photo') || !$user->documents) {
                if ($request->hasFile('id_photo')) {
                    $firstDocument = $user->documents->first();
                    if ($user->documents && $firstDocument && $firstDocument->id_photo_path) {
                        Storage::disk('public')->delete($firstDocument->id_photo_path);
                    }
                    
                    $data['id_photo_path'] = $request->file('id_photo')->store('id_photos', 'public');
                }
            }
            
            if ($request->hasFile('kra_certificate') || !$user->documents) {
                if ($request->hasFile('kra_certificate')) {
                    $firstDocument = $user->documents->first();
                    if ($user->documents && $firstDocument && $firstDocument->kra_certificate_path) {
                        Storage::disk('public')->delete($firstDocument->kra_certificate_path);
                    }
                    
                    $data['kra_certificate_path'] = $request->file('kra_certificate')->store('kra_certificates', 'public');
                }
            }
            
            // SHIF document upload
            if ($request->hasFile('shif_document')) {
                $document = $user->documents->first();
                if ($document && $document->shif_document_path) {
                    Storage::disk('public')->delete($document->shif_document_path);
                }
                $data['shif_document_path'] = $request->file('shif_document')->store('shif_documents', 'public');
            }
            // NSSF document upload
            if ($request->hasFile('nssf_document')) {
                if ($document && $document->nssf_document_path) {
                    Storage::disk('public')->delete($document->nssf_document_path);
                }
                $data['nssf_document_path'] = $request->file('nssf_document')->store('nssf_documents', 'public');
            }

            $user->documents()->updateOrCreate(
                ['user_id' => $user->id],
                $data
            );
            
            $user->update(['documents_submitted' => true]);
            
            return redirect()->route("onboarding.navigate", 'policy-agreement')
                ->with('success', 'Documents uploaded successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Document upload failed: ' . $e->getMessage());
            return back()->withErrors(['upload_error' => 'Failed to upload documents. Please try again.']);
        }
    }
    
    public function signContract(Request $request)
    {
        $user = auth()->user();
        $activeProjectId = $user->getActiveProjectId();

        $request->validate([
            'agreed' => 'required|accepted',
            'signature' => 'required|string',
        ]);

        $contract = $this->getContractForUser($user);

        if (!$contract) {
            return redirect()->back()
                ->with('error', 'No valid contract found for your role and project.');
        }

        try {
            DB::beginTransaction();

            $user->contractSignature()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'contract_id' => $contract->id,
                    'signature' => $request->signature,
                    'agreed_at' => now(),
                    'status' => 'draft',
                    'project_id' => $activeProjectId 
                ]
            );

            $user->update(['contract_signed' => true]);

            DB::commit();

            return redirect()->route('onboarding.navigate', 'final-confirmation')
                ->with('success', 'Contract signed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Contract signing failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to sign contract. Please try again.');
        }
    }

    public function restart(Request $request)
    {
        $user = auth()->user();
        Log::info('Onboarding restart called', [
            'user_id' => $user ? $user->id : null,
            'request' => $request->all()
        ]);
        try {
            DB::beginTransaction();
            $currentContract = UserContractSignature::where('user_id', $user->id)
                ->latest()
                ->first();

            // Check for individual contract first
            $availableContract = AdminContract::where('user_id', $user->id)
                ->where('contract_category', 'individual')
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->exists();
            // If not found, fallback to group contract
            if (!$availableContract) {
                $availableContract = AdminContract::where('role_id', $user->role_id)
                    ->where('status', 'published')
                    ->where('active_for_onboarding', true)
                    ->where(function($q) use ($user) {
                        $activeProjectId = $user->getActiveProjectId();
                        $q->whereNull('project_id')
                          ->orWhere('project_id', $activeProjectId);
                    })
                    ->whereHas('counties', function ($query) use ($user) {
                        $query->where('counties.id', $user->county_id);
                    })
                    ->exists();
            }

            if (!$availableContract) {
                return redirect()->back()
                    ->with('error', 'No active contract template is available for your role and county. Please contact your administrator.');
            }

            if ($currentContract) {
                if ($currentContract->status === 'terminated') {
                    return redirect()->back()
                        ->with('error', 'Your contract was terminated. Please contact an administrator to restart the onboarding process.');
                }

                if (in_array($currentContract->status, ['inactive', 'expired', 'declined'])) {
                    $currentContract->update([
                        'archived' => true,
                        'archived_at' => now(),
                        'archive_reason' => 'User initiated restart of onboarding process'
                    ]);
                } elseif ($currentContract->status === 'draft') {
                    return redirect()->route('onboarding.navigate', 'personal-info')
                        ->with('info', 'Continuing with your draft onboarding process.');
                }
            }

            $user->update([
                'onboarding_status' => false,
                'contract_signed' => false,
                'documents_submitted' => false,
                'bank_details_submitted' => false
            ]);

            DB::commit();

            Log::info('Onboarding restart successful', [
                'user_id' => $user->id
            ]);
            return redirect()->route('onboarding.navigate', 'personal-info')
                ->with('success', 'Onboarding process started. Please complete all required information.');

        } catch (\Exception $e) {
            DB::rollBack();
            $userId = auth()->check() ? auth()->user()->id : null;
            Log::error('Failed to restart onboarding', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Failed to restart onboarding process. Please try again later.');
        }
    }

    private function contractExists($user)
    {
        return $this->getContractForUser($user) !== null;
    }

    public function getBankBranches(Request $request)
    {
        $bankId = $request->input('bank_id');
        $branches = BankBranch::where('bank_id', $bankId)
            ->select('branch_code as code', 'branch_name as name')
            ->get();
        
        return response()->json($branches);
    }
}