<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityLog extends Model
{
    protected $fillable = [
        'field_activity_id',
        'date',
        'log_data',
    ];
    protected $casts = [
        'log_data' => 'array',
        'date' => 'date',
    ];
}
