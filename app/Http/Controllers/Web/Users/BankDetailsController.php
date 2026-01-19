<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\User;
use Vanguard\UserBankDetail;
use Illuminate\Support\Facades\DB;
use Vanguard\Events\User\UpdatedByAdmin;

class BankDetailsController extends Controller
{
    public function updateBankDetails(Request $request, User $user): RedirectResponse
    {
        $this->authorize('updateBankDetails', $user);
        $validated = $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'bank_branch' => 'required|string|max:191',
            'account_name' => 'required|string|max:191',
            'account_number' => 'required|string|max:191'
        ]);

        try {
            DB::beginTransaction();

            // Log the incoming data
            \Log::info('Updating bank details for user:', [
                'user_id' => $user->id,
                'data' => $validated
            ]);

            $bankDetails = UserBankDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bank_id' => $validated['bank_id'],
                    'bank_branch' => $validated['bank_branch'],
                    'account_name' => $validated['account_name'],
                    'account_number' => $validated['account_number']
                ]
            );

            // If this is the first time adding bank details
            if (!$user->banking_submitted) {
                $user->banking_submitted = true;
                $user->save();
            }

            event(new UpdatedByAdmin($user));

            DB::commit();

            // Log the success
            \Log::info('Bank details updated successfully for user:', [
                'user_id' => $user->id,
                'bank_details_id' => $bankDetails->id
            ]);

            return redirect()->back()
                ->withSuccess(__('Bank details updated successfully.'))
                ->with('tab', 'sensitive');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Failed to update bank details:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(__('Failed to update bank details: ') . $e->getMessage())
                ->withInput()
                ->with('tab', 'sensitive');
        }
    }
}
