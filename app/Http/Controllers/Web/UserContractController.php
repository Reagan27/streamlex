<?php

namespace Vanguard\Http\Controllers\Web;

use Carbon\Carbon;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Vanguard\UserContractSignature;
use Illuminate\Http\Request;

class UserContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    // Admin-only methods
    public function countActiveContracts()
    {
        return AdminContract::where('status', 'published')->count();
    }

    public function countExpiredContracts()
    {
        return AdminContract::where('end_date', '<', now())->count();
    }
}