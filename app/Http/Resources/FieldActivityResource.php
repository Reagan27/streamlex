<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_by_name' => optional($this->user)->name ?? optional($this->user)->first_name . ' ' . optional($this->user)->last_name,
            'expenses' => FieldActivityExpenseResource::collection($this->whenLoaded('expenses')),
            'actual_expenses' => FieldActivityActualExpenseResource::collection($this->whenLoaded('actualExpenses')),
            'logistics' => FieldActivityLogisticResource::collection($this->whenLoaded('logistics')),
            'transport_logs' => FieldActivityTransportLogResource::collection($this->whenLoaded('transportLogs')),
            'timeline' => FieldActivityTimelineResource::collection($this->whenLoaded('timeline')),
            'documents' => FieldActivityDocumentResource::collection($this->whenLoaded('documents')),
            'approvals' => FieldActivityApprovalResource::collection($this->whenLoaded('approvals')),
        ];
    }
}
