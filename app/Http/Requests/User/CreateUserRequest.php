<?php

namespace Vanguard\Http\Requests\User;

use Vanguard\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Vanguard\Role;

class CreateUserRequest extends Request
{
    const ROLE_REGIONAL_COORDINATOR = 'Regional Coordinator';
    const ROLE_COUNTY_COORDINATOR = 'County Coordinator';
    const ROLE_SUPERVISOR = 'Supervisor';
    const ROLE_FIELD_OFFICER = 'Field Officer';

    public function rules(): array
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|unique:users,username',
            'password' => 'required|min:8|confirmed',
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'phone' => 'nullable|string|max:191|unique:users,phone',
            'birthday' => 'nullable|date',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|string|max:20',
            'address' => 'nullable|string|max:191',
            'avatar' => 'nullable|string|max:191',
            'country_id' => 'nullable|exists:countries,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'verified' => 'boolean',
        ];

        $role = Role::find($this->input('role_id'));
        if ($role) {
            switch ($role->name) {
                case self::ROLE_REGIONAL_COORDINATOR:
                    $rules['counties'] = 'required|array';
                    $rules['counties.*'] = 'exists:counties,id';
                    break;
                case self::ROLE_COUNTY_COORDINATOR:
                    $rules['county_id'] = 'required|exists:counties,id';
                    break;
                case self::ROLE_SUPERVISOR:
                    $rules['county_id'] = 'required|exists:counties,id';
                    $rules['subcounty_id'] = 'required|exists:subcounties,id';
                    break;
                case self::ROLE_FIELD_OFFICER:
                    $rules['county_id'] = 'required|exists:counties,id';
                    $rules['subcounty_id'] = 'required|exists:subcounties,id';
                    $rules['ward_id'] = 'required|exists:wards,id';
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'counties.required' => 'Please select at least one county for the Regional Coordinator.',
            'counties.*.exists' => 'One or more selected counties are invalid.',
        ];
    }
}