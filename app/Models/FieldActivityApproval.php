<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldActivityApproval extends Model
{
    protected $fillable = [
        'field_activity_id',
        'approval_level',
        'role',
        'status',
        'comments',
        'approver_name',
        'approver_user_id',
        'approved_at',
    ];

    public function fieldActivity()
    {
        return $this->belongsTo(FieldActivity::class);
    }
}
