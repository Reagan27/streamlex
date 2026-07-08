<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\Http\Controllers\Controller;
use Vanguard\User;
use Vanguard\AdminContract;
use Vanguard\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Vanguard\Mail\ContractApproved;
use Vanguard\Mail\ContractAccepted;
use Vanguard\Mail\ContractDeclined;
use Vanguard\Services\ContractPdfService;
use Vanguard\Services\RoleHierarchyService;
use Illuminate\Support\Facades\Password;
use Vanguard\County;
use Vanguard\Role;
use Vanguard\UserManualBankDetails;

class ApprovalController extends Controller {
    protected $pdfService;
    protected $roleHierarchyService;

    public function __construct(ContractPdfService $pdfService, RoleHierarchyService $roleHierarchyService)
    {
        $this->middleware('auth');
        $this->middleware('permission:contracts.approval');
        $this->pdfService = $pdfService;
        $this->roleHierarchyService = $roleHierarchyService;
    }

    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = User::whereHas('contractSignature', function ($query) use ($currentUser) {
            if (in_array($currentUser->role->name, ['Admin', 'Manager'])) {
                $query->whereIn('status', ['draft', 'approved']);
            } else {
                $query->where('status', 'draft');
            }
        })->with(['role', 'contractSignature', 'county']);
    
        // Apply role-based filtering
        switch ($currentUser->role->name) {
            case 'Regional_Coordinator':
                $counties = $currentUser->counties()->select('counties.id')->pluck('counties.id');
                $query->whereIn('county_id', $counties)
                      ->whereHas('role', function ($q) {
                          $q->where('name', 'County_Coordinator');
                      });
                break;
            case 'County_Coordinator':
                $query->where('county_id', $currentUser->county_id)
                      ->whereHas('role', function ($q) {
                          $q->where('name', 'Supervisor');
                      });
                break;
            case 'Supervisor':
                $query->where('county_id', $currentUser->county_id)
                      ->where('supervisor_id', $currentUser->id)
                      ->whereHas('role', function ($q) {
                          $q->where('name', 'Field_Officer');
                      });
                break;
        }
    
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhereHas('role', function($q) use ($searchTerm) {
                      $q->where('display_name', 'like', "%{$searchTerm}%");
                  });
            });
        }
    
        // Apply county filter
        if ($request->filled('county')) {
            $query->where('county_id', $request->input('county'));
        }
    
        // Apply role filter
        if ($request->filled('role')) {
            $query->where('role_id', $request->input('role'));
        }
    
        // Apply status filter
        if ($request->filled('status')) {
            $query->whereHas('contractSignature', function($q) use ($request) {
                $q->where('status', $request->input('status'));
            });
        }
    
        // Get paginated results
        $users = $query->latest()->paginate(10)->withQueryString();
    
        // Get filter options
        $counties = County::orderBy('name')->get();
        $roles = Role::orderBy('display_name')->get();
    
        $canApprove = $currentUser->hasPermission('contracts.approval');
        $canAccept = $currentUser->hasPermission('contracts.accept');
    
        return view('approval.index', compact(
            'users',
            'counties',
            'roles',
            'canApprove',
            'canAccept'
        ));
    }
    
    public function process(Request $request, User $user)
    {
        $this->validate($request, [
            'status' => 'required|in:approved,accepted,declined',
            'reason' => 'required_if:status,declined',
        ]);
    
        $currentUser = auth()->user();
        $emailConfirmationEnabled = Setting::get('reg_email_confirmation', false);

        // Find the contract signature actually linked to the user (most recently agreed)
        $contractSignature = \Vanguard\UserContractSignature::where('user_id', $user->id)
            ->whereNotNull('signature')
            ->whereNotNull('agreed_at')
            ->latest('agreed_at')
            ->first();

        if (!$contractSignature) {
            return back()->with('error', __('No contract signature found for this user.'));
        }

        // Use the contract linked to the signature
        $contract = $contractSignature->contract;

        // Remove active contract check for approval/acceptance
        if ($request->status === 'accepted') {
            if (!$this->roleHierarchyService->canAccept($currentUser, $user)) {
                return back()->with('error', __('You do not have permission to accept this contract.'));
            }
        } elseif (!$this->roleHierarchyService->canApprove($currentUser, $user)) {
            return back()->with('error', __('You do not have permission to approve this user\'s contract.'));
        }

        $contractSignature->status = $request->status;

        if ($request->status === 'approved') {
            $this->pdfService->generateContract($user, $contractSignature);
            $contractSignature->decline_reason = null;
            
            if ($emailConfirmationEnabled) {
                try {
                    $token = Password::createToken($user);
                    Mail::to($user->email)->send(new ContractApproved($user, $token));
                } catch (\Exception $e) {
                    // Handle email error
                }
            }
        } elseif ($request->status === 'accepted') {
            if ($emailConfirmationEnabled) {
                try {
                    Mail::to($user->email)->send(new ContractAccepted($user));
                } catch (\Exception $e) {
                    // Handle email error
                }
            }
        } elseif ($request->status === 'declined') {
            $contractSignature->decline_reason = $request->reason;
            
            if ($emailConfirmationEnabled) {
                try {
                    Mail::to($user->email)->send(new ContractDeclined($user, $contractSignature->decline_reason));
                } catch (\Exception $e) {
                    // Handle email error
                }
            }
        }

        $contractSignature->save();

        $queryParams = array_filter([
            'page' => $request->query('page', 1),
            'search' => $request->query('search'),
            'county' => $request->query('county'),
            'role' => $request->query('role'),
            'status' => $request->query('status'),
        ]);

        return redirect()->route('approval.index', $queryParams)
            ->with('success', __('Contract status updated successfully.'));
    }
    
    public function show($userId)
    {
        $currentUser = auth()->user();
        $user = User::findOrFail($userId);
        
        if (!$this->roleHierarchyService->canAccess($currentUser, $user)) {
            abort(403, 'Unauthorized action.');
        }
    
        $contractSignature = $user->contractSignature;
        $bankDetails = $user->bankDetails()->with(['bank'])->first();
        $manualBankDetails = UserManualBankDetails::where('user_id', $user->id)->first();
        $userDocuments = $user->documents;
        $contract = AdminContract::where('role_id', $user->role_id)
                                 ->where('status', 'published')
                                 ->latest()
                                 ->first();
    
        $canApprove = $this->roleHierarchyService->canApprove($currentUser, $user);
        $canAccept = $this->roleHierarchyService->canAccept($currentUser, $user);
    
        $queryParams = request()->only(['page', 'search', 'county', 'role', 'status']);
    
        return view('approval.show', compact(
            'user', 
            'bankDetails', 
            'manualBankDetails',
            'contractSignature', 
            'userDocuments', 
            'contract', 
            'canApprove', 
            'canAccept',
            'queryParams'
        ));
    }

    public function testEmail()
    {
        $user = auth()->user();
        if (!$user) {
            return "No authenticated user found.";
        }
        $token = Password::createToken($user);
        Mail::to($user->email)->send(new ContractApproved($user, $token));
        return "Test email sent to " . $user->email;
    }
}
