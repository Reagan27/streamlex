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
        $contractSignature = $user->contractSignature;

        return view('dashboard.index', compact('contractSignature'));
    }
}