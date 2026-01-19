<?php

namespace Vanguard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recipient' => $this->recipient,
            'message' => $this->message,
            'status' => $this->status,
            'status_description' => $this->statusDescription,
            'message_id' => $this->message_id,
            'message_cost' => $this->message_cost,
            'is_template' => $this->is_template,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }

    /**
     * List of allowed includes for eager loading.
     *
     * @return array
     */
    public static function allowedIncludes(): array
    {
        return [];
    }
}
