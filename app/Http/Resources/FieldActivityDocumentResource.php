<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldActivityDocumentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'field_activity_id' => $this->field_activity_id,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'uploaded_by' => $this->uploaded_by,
        ];
    }
}
