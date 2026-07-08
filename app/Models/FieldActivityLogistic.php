<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityLogistic extends Model
{
    protected $fillable = [
        'activity_id', 'from_location', 'to_location', 'mode', 'cost'
    ];

    public function activity()
    {
        return $this->belongsTo(FieldActivity::class, 'activity_id');
    }
}
