<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
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

class OnboardingController extends Controller
{
    protected $steps = [
        'welcome',
        'personal-info',
        'banking-details',
        'documents',
        'policy-agreement',
        'contract',
        'final-confirmation'
    ];

    public function index()
    {
        $user = auth()->user();
        $contractSignature = $user->contractSignature;
    
        if ($user->onboarding_status && !$this->shouldRestartOnboarding($contractSignature)) {
            return redirect('/')->with('info', 'You have already completed the onboarding process.');
        }
    
        // Get contract based on role
        $contract = $this->getContractForUser($user);
        
        // Add debug logging
        Log::info('Contract Check:', [
            'user_id' => $user->id,
            'role_id' => $user->role_id,
            'is_regional_coordinator' => ($user->role_id == 2),
            'contract_found' => ($contract ? 'Yes' : 'No'),
            'contract_active' => ($contract ? $contract->active_for_onboarding : 'N/A')
        ]);
    
        $hasContract = $contract !== null && $contract->active_for_onboarding;
    
        // Get previous contract status if exists
        $prevContractStatus = null;
        if ($contractSignature) {
            $prevContractStatus = $contractSignature->status;
        }
    
        return view('onboarding.welcome', compact('contract', 'hasContract', 'prevContractStatus'));
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
            // case 'documents':
            //     $data = $request->validate([
            //         'id_number' => 'required|string',
            //         'kra_pin' => 'required|string',
            //         'id_photo' => $request->has('replace_id_photo') ? 'required|file|mimes:pdf,jpg,jpeg,png' : 'nullable',
            //         'kra_certificate' => $request->has('replace_kra_certificate') ? 'required|file|mimes:pdf,jpg,jpeg,png' : 'nullable',
            //     ]);
                // if ($request->hasFile('id_photo') || !$user->documents) {
                //     $data['id_photo_path'] = $request->file('id_photo')->store('id_photos', 'public');
                // }
    
                // if ($request->hasFile('kra_certificate') || !$user->documents) {
                //     $data['kra_certificate_path'] = $request->file('kra_certificate')->store('kra_certificates', 'public');
                // }
    
                // $user->documents()->updateOrCreate([], $data);
                // break;
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

    public function completeFinalConfirmation()
{
    try {
        DB::beginTransaction();
        
        $user = auth()->user();
        
        // Get the latest contract signature
        $contractSignature = UserContractSignature::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($contractSignature) {
            // Update contract status to accepted
            $contractSignature->update([
                'status' => 'draft',
                'agreed_at' => now()
            ]);
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
        
        return redirect()->back()
            ->with('error', 'Failed to complete onboarding process. Please try again.');
    }
}

    public function navigate(Request $request, $step)
    {
        $user = auth()->user()->load(['documents', 'bankDetails']);
        if ($user->onboarding_status) {
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
            // Load the latest contract signature
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


    private function storeBankingDetails(Request $request)
    {
        $user = auth()->user();
        
        $validatedData = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'bank_branch_code' => 'required|exists:bank_branches,branch_code',
            'account_name' => [
                'required',
                'string',
                'max:255',
                function($attribute, $value, $fail) {
                    if (str_word_count($value) < 2) {
                        $fail('The account name must contain at least two names.');
                    }
                },
            ],
            'account_number' => 'required|string|max:255',
        ]);
        
        $bankBranch = BankBranch::where('bank_id', $validatedData['bank_id'])
            ->where('branch_code', $validatedData['bank_branch_code'])
            ->first();
        
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
        // Set maximum file size (in kilobytes)
        $maxFileSize = 32768; // 32MB
        
        // Custom validation messages
        $messages = [
            'id_photo.max' => 'ID Photo must not be larger than 32MB',
            'kra_certificate.max' => 'KRA Certificate must not be larger than 32MB',
        ];
        
        // Validate the request
        $data = $request->validate([
            'id_number' => ['required', 'regex:/^[0-9]{5,9}$/'],
            'kra_pin' => 'required|string|size:11',
            'id_photo' => $request->has('replace_id_photo') 
                ? "required|file|mimes:pdf,jpg,jpeg,png|max:$maxFileSize" 
                : 'nullable',
            'kra_certificate' => $request->has('replace_kra_certificate') 
                ? "required|file|mimes:pdf,jpg,jpeg,png|max:$maxFileSize" 
                : 'nullable',
        ], $messages);
    
        try {
            $user = auth()->user();
            
            // Handle ID Photo upload
            if ($request->hasFile('id_photo') || !$user->documents) {
                if ($request->hasFile('id_photo')) {
                    // Delete old file if it exists
                    if ($user->documents && $user->documents->id_photo_path) {
                        Storage::disk('public')->delete($user->documents->id_photo_path);
                    }
                    
                    $data['id_photo_path'] = $request->file('id_photo')->store('id_photos', 'public');
                }
            }
            
            // Handle KRA Certificate upload
            if ($request->hasFile('kra_certificate') || !$user->documents) {
                if ($request->hasFile('kra_certificate')) {
                    // Delete old file if it exists
                    if ($user->documents && $user->documents->kra_certificate_path) {
                        Storage::disk('public')->delete($user->documents->kra_certificate_path);
                    }
                    
                    $data['kra_certificate_path'] = $request->file('kra_certificate')->store('kra_certificates', 'public');
                }
            }
            
            // Create or update document records
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
    
        $request->validate([
            'agreed' => 'required|accepted',
            'signature' => 'required|string',
        ]);
    
        $contract = $this->getContractForUser($user);
    
        if (!$contract) {
            return redirect()->back()
                ->with('error', 'No valid contract found for your role.');
        }
    
        try {
            DB::beginTransaction();
    
            // Create or update contract signature with draft status
            $user->contractSignature()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'contract_id' => $contract->id,
                    'signature' => $request->signature,
                    'agreed_at' => now(),
                    'status' => 'draft' // Set initial status as draft
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
        
        try {
            DB::beginTransaction();
            
            // Get current contract
            $currentContract = UserContractSignature::where('user_id', $user->id)
                ->latest()
                ->first();

            // Check if there's an available contract template
            $availableContract = AdminContract::where('role_id', $user->role_id)
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->whereHas('counties', function ($query) use ($user) {
                    $query->where('counties.id', $user->county_id);
                })
                ->exists();

            if (!$availableContract) {
                return redirect()->back()
                    ->with('error', 'No active contract template is available for your role and county. Please contact your administrator.');
            }

            // Handle existing contract
            if ($currentContract) {
                // Only terminated contracts need admin intervention
                if ($currentContract->status === 'terminated') {
                    return redirect()->back()
                        ->with('error', 'Your contract was terminated. Please contact an administrator to restart the onboarding process.');
                }

                // For inactive, expired, or declined contracts, auto-archive
                if (in_array($currentContract->status, ['inactive', 'expired', 'declined'])) {
                    $currentContract->update([
                        'archived' => true,
                        'archived_at' => now(),
                        'archive_reason' => 'User initiated restart of onboarding process'
                    ]);
                }
                // If contract is in draft status, just continue with that
                elseif ($currentContract->status === 'draft') {
                    return redirect()->route('onboarding.navigate', 'personal-info')
                        ->with('info', 'Continuing with your draft onboarding process.');
                }
            }

            // Reset user onboarding status
            $user->update([
                'onboarding_status' => false,
                'contract_signed' => false,
                'documents_submitted' => false,
                'bank_details_submitted' => false
            ]);

            DB::commit();

            return redirect()->route('onboarding.navigate', 'personal-info')
                ->with('success', 'Onboarding process started. Please complete all required information.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to restart onboarding', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to restart onboarding process. Please try again later.');
        }
    }
    
    private function getContractForUser($user)
    {
        Log::info("User ID: " . $user->id);
        Log::info("User Role ID: " . $user->role_id);
    
        if ($user->role_id == 2) { // Regional Coordinator
            Log::info("User is a Regional Coordinator");
            
            // Get all counties assigned to this Regional Coordinator
            $assignedCountyIds = \DB::table('regional_coordinator_counties')
                ->where('user_id', $user->id)
                ->pluck('county_id');
                
            Log::info("Assigned County IDs: " . $assignedCountyIds->implode(', '));
    
            if ($assignedCountyIds->isEmpty()) {
                Log::warning("Regional Coordinator has no assigned counties");
                return null;
            }
    
            // Get contract that matches the role and any of the assigned counties
            $contract = AdminContract::where('role_id', $user->role_id)
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->whereHas('counties', function ($query) use ($assignedCountyIds) {
                    $query->whereIn('county_id', $assignedCountyIds);
                })
                ->latest()
                ->first();
    
            Log::info("Contract found: " . ($contract ? "Yes, ID: {$contract->id}" : "No"));
            return $contract;
        } else {
            // For other roles, use the existing county_id check
            if (!$user->county_id) {
                Log::warning("User has no county_id assigned");
                return null;
            }
    
            return AdminContract::where('role_id', $user->role_id)
                ->where('status', 'published')
                ->where('active_for_onboarding', true)
                ->whereHas('counties', function ($query) use ($user) {
                    $query->where('county_id', $user->county_id);
                })
                ->latest()
                ->first();
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