<?php

namespace Vanguard\Http\Controllers\Web\Profile;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\UserDocument;
use Vanguard\UserBankDetail;
use Vanguard\UserManualBankDetails;
use Vanguard\Bank;
use Vanguard\BankBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SensitiveInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateSensitiveInfo(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        try {
            DB::beginTransaction();

            // Update documents if provided
            if ($request->filled(['id_number', 'kra_pin'])) {
                $userDocument = UserDocument::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'id_number' => $request->id_number,
                        'kra_pin' => $request->kra_pin
                    ]
                );
            }

            // Update bank details if provided
            if ($request->filled(['bank_id', 'account_name', 'account_number'])) {
                // Update main bank details
                $bankDetails = UserBankDetail::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'bank_id' => $request->bank_id,
                        'bank_branch' => $request->boolean('use_manual_details') ? null : $request->bank_branch_code,
                        'account_name' => $request->account_name,
                        'account_number' => $request->account_number
                    ]
                );

                // Update manual bank details if using manual input
                if ($request->boolean('use_manual_details')) {
                    UserManualBankDetails::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'manual_branch_name' => $request->manual_branch_name,
                            'manual_branch_code' => $request->manual_branch_code,
                            'use_manual_details' => true
                        ]
                    );
                } else {
                    // If not using manual details, delete any existing manual details
                    UserManualBankDetails::where('user_id', $user->id)->delete();
                }
            }

            DB::commit();

            return redirect()
                ->route('profile')
                ->with('success', __('Sensitive information updated successfully.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update sensitive information: ' . $e->getMessage());

            return redirect()
                ->route('profile')
                ->with('error', __('Failed to update sensitive information: ') . $e->getMessage());
        }
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