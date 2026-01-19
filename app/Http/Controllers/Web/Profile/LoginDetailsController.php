<?php

namespace Vanguard\Http\Controllers\Web\Profile;

use Illuminate\Http\RedirectResponse;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\UpdateProfilePasswordRequest;
use Vanguard\Repositories\User\UserRepository;

class LoginDetailsController extends Controller
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function update(UpdateProfilePasswordRequest $request): RedirectResponse
    {
        $data = $request->only(['password', 'password_confirmation']);

        // If password is not provided, return with an error
        if (! $data['password']) {
            return redirect()->route('profile', ['tab' => 'password'])
                ->withErrors(['password' => __('Password field is required.')]);
        }

        $this->users->update(auth()->id(), $data);

        return redirect()->route('profile', ['tab' => 'password'])
            ->withSuccess(__('Password updated successfully.'));
    }
}