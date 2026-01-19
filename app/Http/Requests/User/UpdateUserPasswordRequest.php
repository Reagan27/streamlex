<?php

namespace Vanguard\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Vanguard\User;

class UpdateUserPasswordRequest extends FormRequest
{
    public function authorize()
    {
        $currentUser = $this->user();
        $userBeingUpdated = $this->route('user');
        return $currentUser->hasRole('Admin') || $currentUser->can('update', User::class);
    }

    public function rules()
    {
        return [
            'password' => 'required|min:8|confirmed',
        ];
    }
}