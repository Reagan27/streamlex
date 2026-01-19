<?php
namespace Vanguard\Http\Controllers\Web\Users;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Vanguard\Events\User\Banned;
use Vanguard\Events\User\UpdatedByAdmin;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\UpdateDetailsRequest;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Support\Enum\UserStatus;
use Vanguard\User;
use Vanguard\Role;
use Vanguard\AdminContract;
use Vanguard\UserBankDetail;
use Vanguard\UserContractSignature;
use Vanguard\UserDocument;
use Illuminate\Support\Facades\DB;

class DetailsController extends Controller 
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function update(User $user, UpdateDetailsRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            // Get current and new role
            $currentRole = $user->role;
            $newRole = Role::findOrFail($data['role_id']);

            // Base user data
            $userData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => $data['status'],
                'role_id' => $data['role_id'],
                'completed' => $request->has('completed')
            ];

            // Auto-onboarding for admin roles
            if ($currentRole->id != $newRole->id && in_array($newRole->id, [1, 7, 9])) {
                $userData = array_merge($userData, [
                    'onboarding_completed_at' => Carbon::now(),
                    'banking_submitted' => 1,
                    'documents_submitted' => 1,
                    'contract_signed' => 1,
                    'onboarding_status' => 1
                ]);

                $this->createAutoOnboardingData($user, $data);
            }

            // Handle location data
            $locationData = $this->handleLocationData($user, $request, $newRole);
            
            // Update user with merged data
            $this->users->update($user->id, array_merge($userData, $locationData));

            // Fire events
            event(new UpdatedByAdmin($user));

            if ($this->userWasBanned($user, $request)) {
                event(new Banned($user));
            }

            DB::commit();
            return redirect()->back()->withSuccess(__('User updated successfully.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(__('Failed to update user: ') . $e->getMessage())
                ->withInput();
        }
    }

    private function createAutoOnboardingData(User $user, array $data): void
    {
        // Create bank details
        UserBankDetail::create([
            'user_id' => $user->id,
            'bank_id' => 1,
            'bank_branch' => 'Auto-generated',
            'account_name' => $data['first_name'] . ' ' . $data['last_name'],
            'account_number' => 'AUTO-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
        ]);

        // Create documents
        UserDocument::create([
            'user_id' => $user->id,
            'id_number' => 'AUTO-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'id_photo_path' => 'auto_generated/id_photo.jpg',
            'kra_pin' => 'AUTO-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'kra_certificate_path' => 'auto_generated/kra_certificate.pdf',
        ]);

        // Create contract signature
        $contract = AdminContract::where('status', 'published')->latest()->first();
        if ($contract) {
            UserContractSignature::create([
                'user_id' => $user->id,
                'contract_id' => $contract->id,
                'signature' => 'Auto-generated Signature',
                'agreed_at' => Carbon::now(),
                'status' => 'accepted',
            ]);
        }
    }

    private function handleLocationData(User $user, Request $request, Role $newRole): array 
    {
        $locationData = [];
        
        switch ($newRole->name) {
            case 'Regional_Coordinator':
                $locationData = [
                    'county_id' => null,
                    'subcounty_id' => null,
                    'ward_id' => null
                ];
                
                if ($request->has('counties')) {
                    $user->counties()->sync($request->input('counties', []));
                }
                break;

            case 'County_Coordinator':
            case 'Supervisor':
            case 'Field_Officer':
                $locationData = [
                    'county_id' => $request->filled('county_id') ? $request->county_id : $user->county_id,
                    'subcounty_id' => $request->filled('subcounty_id') ? $request->subcounty_id : $user->subcounty_id,
                    'ward_id' => $request->filled('ward_id') ? $request->ward_id : $user->ward_id
                ];

                if ($newRole->name === 'County_Coordinator') {
                    $locationData['subcounty_id'] = null;
                    $locationData['ward_id'] = null;
                } elseif ($newRole->name === 'Supervisor') {
                    $locationData['ward_id'] = null;
                }

                $user->counties()->detach();
                break;
        }

        return $locationData;
    }

    public function updateCounties(User $user, Request $request): RedirectResponse
    {
        $this->authorize('updateCounties', $user);

        $request->validate([
            'counties' => 'required|array',
            'counties.*' => 'exists:counties,id'
        ]);

        try {
            DB::beginTransaction();
            $user->counties()->sync($request->counties);
            DB::commit();
            return redirect()->back()
                ->withSuccess(__('Assigned counties updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(__('Failed to update assigned counties.'));
        }
    }

    private function userWasBanned(User $user, Request $request): bool
    {
        return $user->status != $request->status 
            && $request->status == UserStatus::BANNED->value;
    }
}