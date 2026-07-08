<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyAcknowledgement extends Model
{
    protected $table = 'policy_acknowledgements';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'employee_name',
        'acknowledged_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
    ];
}