<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldActivityApprovalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'field_activity_id' => $this->field_activity_id,
            'approved_by' => $this->approved_by,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}
