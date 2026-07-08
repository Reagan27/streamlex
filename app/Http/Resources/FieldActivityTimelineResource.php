<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldActivityTimelineResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'field_activity_id' => $this->field_activity_id,
            'event' => $this->event,
            'timestamp' => $this->timestamp,
            'notes' => $this->notes,
        ];
    }
}
