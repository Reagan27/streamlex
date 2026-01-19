<?php

namespace Vanguard\Http\Controllers\Web\Users;

use Illuminate\Http\RedirectResponse;
use Vanguard\Events\User\UpdatedByAdmin;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Http\Requests\User\UpdateUserPasswordRequest;
use Vanguard\Repositories\User\UserRepository;
use Vanguard\User;

class LoginDetailsController extends Controller
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function update(User $user, UpdateUserPasswordRequest $request): RedirectResponse
    {
        $data = $request->only(['password', 'password_confirmation']);

        if (! $data['password']) {
            return redirect()->route('users.edit', $user->id)
                ->withErrors(['password' => __('Password field is required.')])
                ->with('tab', 'password');
        }

        $this->users->update($user->id, $data);

        event(new UpdatedByAdmin($user));

        return redirect()->route('users.edit', $user->id)
            ->withSuccess(__('Password updated successfully.'))
            ->with('tab', 'password');
    }
}