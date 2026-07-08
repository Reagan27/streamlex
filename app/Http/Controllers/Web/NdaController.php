<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Vanguard\Services\NdaPdfService;
use Vanguard\Http\Controllers\Controller;

class NdaController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $nda_date = now()->format('d');
        $nda_month = now()->format('F');
        $nda_year = now()->format('Y');
        $nda_signed_date = $user->nda_signed_at ? $user->nda_signed_at->format('d/m/Y') : null;
        $contract_signature = \Vanguard\UserContractSignature::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->latest('agreed_at')
            ->first();
        return view('nda', compact('user', 'nda_date', 'nda_month', 'nda_year', 'nda_signed_date', 'contract_signature'));
    }

    public function accept(Request $request)
    {
        $user = Auth::user();
        // Mark NDA as accepted and set signed date
        $user->nda_accepted = true;
        $user->nda_signed_at = now();
        $user->save();

        // No onboarding check or update needed
        // The NDA view will display the contract signature if it exists

        return redirect()->route('policy.acknowledgement');
    }

    public function download(Request $request)
    {
        $user = Auth::user();
        $nda_date = now()->format('d');
        $nda_month = now()->format('F');
        $nda_year = now()->format('Y');
        $nda_signed_date = $user->nda_signed_at ? $user->nda_signed_at->format('d/m/Y') : null;
        $contract_signature = \Vanguard\UserContractSignature::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->latest('agreed_at')
            ->first();
        $html = view('nda', compact('user', 'nda_date', 'nda_month', 'nda_year', 'nda_signed_date', 'contract_signature'))->render();
        $pdfService = new NdaPdfService();
        $pdfContent = $pdfService->generateNda($html);
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="NDA-' . $user->name . '.pdf"');
    }
}
