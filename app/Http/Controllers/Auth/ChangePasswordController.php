<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function showForceChangeForm()
    {
        return view('auth.force-password-change');   // ← correct view path
    }

    public function forceUpdate(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        $user->update([
            'password'             => Hash::make($request->password),
            'first_authentication' => false,
            'initial_password'     => false,
        ]);

        return redirect()->route('dashboard')   // ← correct route name
            ->with('success', 'Password changed successfully! Welcome to Streamline.');
    }
}
