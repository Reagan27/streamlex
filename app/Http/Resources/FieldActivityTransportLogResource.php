<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldActivityTransportLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'field_activity_id' => $this->field_activity_id,
            'vehicle' => $this->vehicle,
            'driver' => $this->driver,
            'distance' => $this->distance,
            'notes' => $this->notes,
        ];
    }
}
