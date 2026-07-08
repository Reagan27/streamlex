<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldActivityTransport extends Model
{
    use HasFactory;

    protected $table = 'field_activity_transport';

    protected $fillable = [
        'field_activity_id',
        'from_location',
        'to_location',
        'mode',
        'planned_cost',
        'actual_cost',
        'departure_lat',
        'departure_lng',
        'arrival_lat',
        'arrival_lng',
        'departure_time',
        'arrival_time',
        'gps_status',
    ];

    public function fieldActivity(): BelongsTo
    {
        return $this->belongsTo(FieldActivity::class);
    }
}
