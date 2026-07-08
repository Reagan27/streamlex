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
use Vanguard\Models\UserEducationCertificate;

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

    public function show(Request $request): View
    {
        $authUser = auth()->user();
        $profileUser = $authUser;
        if ($request->filled('user_id') && $authUser->hasRole(['Admin', 'Manager', 'Finance'])) {
            $profileUser = User::findOrFail($request->user_id);
        }
        $contractSignature = $profileUser->contractSignature;
        $userDocument = UserDocument::where('user_id', $profileUser->id)->first();
        $bankDetails = $profileUser->bankDetails;
        $certificates = UserEducationCertificate::where('user_id', $profileUser->id)->get();

        // Only load banks if user has permission to manage sensitive info
        $banks = null;
        if ($authUser->hasPermission('sensitive.information.manage')) {
            $banks = Bank::orderBy('name')->get();
        }

        $counties = $this->counties->lists();
        $subcounties = $profileUser->county_id ? $this->subcounties->lists($profileUser->county_id) : collect();
        $wards = $profileUser->subcounty_id ? $this->wards->lists($profileUser->subcounty_id) : collect();

        return view('user.profile', [
            'profileUser' => $profileUser,
            'contractSignature' => $contractSignature,
            'userDocument' => $userDocument,
            'bankDetails' => $bankDetails,
            'banks' => $banks,
            'counties' => [0 => __('Select a County')] + $counties->toArray(),
            'subcounties' => $subcounties,
            'wards' => $wards,
            'canManageSensitiveInfo' => $authUser->hasPermission('sensitive.information.manage'),
            'canViewSensitiveInfo' => $authUser->hasPermission(['sensitive.information.view', 'sensitive.information.manage'], false),
            'certificates' => $certificates,
        ]);
    }
}