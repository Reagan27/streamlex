<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldActivityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string',
            // Add other fields as needed
        ];
    }
}
