<?php
namespace Vanguard\Http\Controllers\Web\Profile;

use Vanguard\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Repositories\County\CountyRepository;
use Vanguard\Repositories\Role\RoleRepository;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\Repositories\Subcounty\SubcountyRepository;
use Vanguard\Repositories\Ward\WardRepository;
use Illuminate\Validation\Rule;
use Vanguard\UserDocument;
use Illuminate\Support\Facades\Gate;
use Vanguard\Bank;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly RoleRepository $roles,
        private readonly CountyRepository $counties,
        private readonly SubcountyRepository $subcounties,
        private readonly WardRepository $wards
    ) {
        $this->middleware('auth');
    }

    public function show(): View
    {
        $user = auth()->user();
        $contractSignature = $user->contractSignature;
        $userDocument = UserDocument::where('user_id', $user->id)->first();
        $bankDetails = $user->bankDetails;
        
        // Only load banks if user has permission to manage sensitive info
        $banks = null;
        if ($user->hasPermission('sensitive.information.manage')) {
            $banks = Bank::orderBy('name')->get();
        }

        $counties = $this->counties->lists();
        $subcounties = $user->county_id ? $this->subcounties->lists($user->county_id) : collect();
        $wards = $user->subcounty_id ? $this->wards->lists($user->subcounty_id) : collect();

        return view('user.profile', [
            'user' => $user,
            'contractSignature' => $contractSignature,
            'userDocument' => $userDocument,
            'bankDetails' => $bankDetails,
            'banks' => $banks,
            'counties' => [0 => __('Select a County')] + $counties->toArray(),
            'subcounties' => $subcounties,
            'wards' => $wards,
            'canManageSensitiveInfo' => $user->hasPermission('sensitive.information.manage'),
            'canViewSensitiveInfo' => $user->hasPermission(['sensitive.information.view', 'sensitive.information.manage'], false)
        ]);
    }
}