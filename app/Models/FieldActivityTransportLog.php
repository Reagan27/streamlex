<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityTransportLog extends Model
{
    protected $fillable = [
        'activity_id', 'from_location', 'to_location', 'mode', 'cost', 'gps_departure_lat', 'gps_departure_lon', 'gps_departure_time', 'gps_arrival_lat', 'gps_arrival_lon', 'gps_arrival_time', 'gps_status'
    ];

    public function activity()
    {
        return $this->belongsTo(FieldActivity::class, 'activity_id');
    }
}
