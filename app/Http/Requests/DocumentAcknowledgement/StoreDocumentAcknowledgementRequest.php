<?php

namespace Vanguard\Http\Requests\DocumentAcknowledgement;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentAcknowledgementRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasPermission('compliance.create');
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'original_file' => 'nullable|file|mimes:pdf|max:5120',
            'signature_page' => 'required|integer|min:1',
            'signature_x' => 'required|numeric|min:0',
            'signature_y' => 'required|numeric|min:0',
        ];
    }
}
