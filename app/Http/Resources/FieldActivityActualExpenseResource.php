<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldActivityActualExpenseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'field_activity_id' => $this->field_activity_id,
            'item' => $this->item,
            'amount' => $this->amount,
            'notes' => $this->notes,
        ];
    }
}
