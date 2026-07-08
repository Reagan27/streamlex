<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Contracts\View\View;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (session()->has('verified')) {
            session()->flash('success', __('E-Mail verified successfully.'));
        }

        $user = Auth::user();
        $activeProjectId = session('active_project_id') ?? $user->getActiveProjectId();
        
        // Get contract signature with project filter if needed
        $contractSignature = $user->contractSignature;
        
        if ($activeProjectId && $contractSignature) {
            // Filter contract signature by project
            $contractSignature = $contractSignature
                ->whereHas('user.projects', function ($q) use ($activeProjectId) {
                    $q->where('projects.id', $activeProjectId);
                })->first();
        }

       
        return view('dashboard.index', [
            'contractSignature' => $contractSignature,
            'activeProjectId' => $activeProjectId,
            'currentUser' => $user
        ]);
    }
}