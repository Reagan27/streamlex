<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Vanguard\UserContractSignature;

class UserContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $user = Auth::user();
        $contractSignature = $user->contractSignature;

        if (!$contractSignature || $contractSignature->status !== 'accepted') {
            return response('Your contract is not available for viewing.', 403);
        }

        $contractPath = storage_path("app/contracts/contract_{$user->id}.pdf");

        if (!Storage::disk('local')->exists("contracts/contract_{$user->id}.pdf")) {
            return response('Contract file not found.', 404);
        }

        return response()->file($contractPath);
    }

    public function download()
    {
        $user = Auth::user();
        $contractSignature = $user->contractSignature;

        if (!$contractSignature || $contractSignature->status !== 'accepted') {
            return back()->with('error', 'Your contract is not available for download.');
        }

        $contractPath = storage_path("app/contracts/contract_{$user->id}.pdf");

        if (!Storage::disk('local')->exists("contracts/contract_{$user->id}.pdf")) {
            return back()->with('error', 'Contract file not found.');
        }

        return response()->download($contractPath, "contract_{$user->id}.pdf");
    }


    public function index()
    {
        $user = Auth::user();
        $contractSignature = $user->contractSignature;

        // Add debug information
        $debug = [
            'user_id' => $user->id,
            'has_contract_signature' => $contractSignature ? 'Yes' : 'No',
            'contract_status' => $contractSignature ? $contractSignature->status : 'N/A',
        ];

        return view('contracts.user_contract', compact('contractSignature', 'debug'));
    }

    // These methods should be moved to an admin-only controller
    public function countActiveContracts()
    {
        // Add admin check here
        if (!Auth::user()->hasRole('Admin')) {
            return response('Unauthorized', 403);
        }

        return UserContractSignature::where('status', 'accepted')
            ->where('created_at', '>=', Carbon::now()->subDays(365))
            ->count();
    }

    public function countExpiredContracts()
    {
        // Add admin check here
        if (!Auth::user()->hasRole('Admin')) {
            return response('Unauthorized', 403);
        }

        return UserContractSignature::where('status', 'accepted')
            ->where('created_at', '<', Carbon::now()->subDays(365))
            ->count();
    }
}