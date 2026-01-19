<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\User;
use Vanguard\UserDocument;

class SensitiveInfoController extends Controller
{
    public function updateSensitiveInfo(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'id_number' => 'required|string|max:255',
            'kra_pin' => 'required|string|max:255',
        ]);

        $userDocument = UserDocument::firstOrNew(['user_id' => $user->id]);
        $userDocument->id_number = $request->id_number;
        $userDocument->kra_pin = $request->kra_pin;
        $userDocument->save();

        return redirect()->back()->with('success', __('Sensitive information updated successfully.'));
    }
}