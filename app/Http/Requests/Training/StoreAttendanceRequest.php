<?php

namespace Vanguard\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id_number' => ['required', 'regex:/^[0-9]{5,9}$/'],
            'signature' => 'required|string'
        ];

        // Add additional validation rules for new registrations
        if (!$this->has('is_existing')) {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string',
                'email' => 'required|email'
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'id_number.regex' => 'The ID number must be between 5 and 9 digits.',
            'signature.required' => 'Please provide your signature to confirm attendance.'
        ];
    }
}