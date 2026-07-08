<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityTimeline extends Model
{
    protected $fillable = [
        'activity_id', 'text', 'icon', 'color', 'created_by'
    ];

    public function activity()
    {
        return $this->belongsTo(FieldActivity::class, 'activity_id');
    }
}
