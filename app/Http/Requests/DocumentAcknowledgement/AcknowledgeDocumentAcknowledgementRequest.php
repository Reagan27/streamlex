<?php

namespace Vanguard\Http\Requests\DocumentAcknowledgement;

use Illuminate\Foundation\Http\FormRequest;

class AcknowledgeDocumentAcknowledgementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'accepted' => 'required|accepted',
            'signature' => 'required|string',
        ];
    }
}
