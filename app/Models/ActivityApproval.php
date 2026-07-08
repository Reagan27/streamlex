<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityApproval extends Model
{
    protected $fillable = [
        'field_activity_id', 'user_id', 'action', 'comment'
    ];

    public function activity()
    {
        return $this->belongsTo(FieldActivity::class, 'field_activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
