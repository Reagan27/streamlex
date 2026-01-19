<?php

namespace Vanguard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'status' => $this->status,
            'serial_number' => $this->serial_number,
            'imei_number' => $this->imei_number,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }

    public static function allowedIncludes(): array
    {
        return [];
    }
}
